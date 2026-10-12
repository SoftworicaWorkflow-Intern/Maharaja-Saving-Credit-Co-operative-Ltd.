<?php

namespace App\Services;

use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class MembershipApplicationService
{
    /**
     * @param array{name: string, email: string, phone: string, address: string, password: string} $data
     */
    public function submit(array $data, ?UploadedFile $identityDocument): array
    {
        $documentPath = $identityDocument?->store('membership-applications', 'local');

        if ($identityDocument && ! $documentPath) {
            throw new RuntimeException('The application document could not be stored.');
        }

        try {
            return DB::transaction(function () use ($data, $documentPath, $identityDocument): array {
                $user = User::query()->create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                ]);

                $application = $user->membershipApplications()->create([
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'status' => MembershipApplication::PENDING,
                    'submitted_at' => now(),
                ]);

                if ($documentPath && $identityDocument) {
                    $application->documents()->create([
                        'uploaded_by' => $user->id,
                        'path' => $documentPath,
                        'mime_type' => $identityDocument->getMimeType(),
                    ]);
                }

                $application->events()->create([
                    'actor_id' => $user->id,
                    'event' => 'application_submitted',
                    'to_status' => MembershipApplication::PENDING,
                ]);

                return [$user, $application];
            });
        } catch (Throwable $exception) {
            if ($documentPath) {
                Storage::disk('local')->delete($documentPath);
            }

            throw $exception;
        }
    }

    public function review(
        MembershipApplication $application,
        User $reviewer,
        string $decision,
        ?string $note,
    ): MembershipApplication {
        $decisions = ['start_review', 'request_documents', 'approve', 'reject'];

        if (! in_array($decision, $decisions, true)) {
            abort(404);
        }

        return DB::transaction(function () use ($application, $reviewer, $decision, $note): MembershipApplication {
            $application = MembershipApplication::query()
                ->lockForUpdate()
                ->findOrFail($application->id);

            if (! in_array($application->status, MembershipApplication::OPEN_STATUSES, true)) {
                throw ValidationException::withMessages([
                    'application' => 'This application has already been finalized.',
                ]);
            }

            $nextStatus = match ($decision) {
                'start_review' => MembershipApplication::UNDER_REVIEW,
                'request_documents' => MembershipApplication::DOCUMENTS_REQUIRED,
                'approve' => MembershipApplication::APPROVED,
                'reject' => MembershipApplication::REJECTED,
            };
            $previousStatus = $application->status;

            if ($decision === 'approve') {
                if ($application->user()->firstOrFail()->member()->exists()) {
                    throw ValidationException::withMessages([
                        'application' => 'This applicant already has a membership record.',
                    ]);
                }

                $member = $application->user()->firstOrFail()->member()->create([
                    'membership_application_id' => $application->id,
                    'phone' => $application->phone,
                    'address' => $application->address,
                    'status' => 'active',
                    'joined_at' => now(),
                ]);
                $member->member_number = 'MBR-'.str_pad((string) $member->id, 6, '0', STR_PAD_LEFT);
                $member->save();
            }

            $application->status = $nextStatus;
            $application->review_reason = $note;
            $application->reviewed_by = $reviewer->id;
            $application->save();

            $application->events()->create([
                'actor_id' => $reviewer->id,
                'event' => match ($decision) {
                    'start_review' => 'review_started',
                    'request_documents' => 'documents_requested',
                    'approve' => 'application_approved',
                    'reject' => 'application_rejected',
                },
                'from_status' => $previousStatus,
                'to_status' => $nextStatus,
                'note' => $note,
            ]);

            return $application;
        });
    }

    /**
     * @param array<int, UploadedFile> $files
     */
    public function submitAdditionalDocuments(
        MembershipApplication $application,
        User $applicant,
        array $files,
    ): MembershipApplication {
        abort_unless(
            $application->user_id === $applicant->id
                && $application->status === MembershipApplication::DOCUMENTS_REQUIRED,
            403,
        );

        $paths = [];

        try {
            foreach ($files as $file) {
                $path = $file->store('membership-applications', 'local');

                if (! $path) {
                    throw new RuntimeException('An application document could not be stored.');
                }

                $paths[] = [$path, $file->getMimeType()];
            }

            return DB::transaction(function () use ($application, $applicant, $paths): MembershipApplication {
                $lockedApplication = MembershipApplication::query()
                    ->lockForUpdate()
                    ->findOrFail($application->id);

                abort_unless($lockedApplication->status === MembershipApplication::DOCUMENTS_REQUIRED, 403);

                foreach ($paths as [$path, $mimeType]) {
                    $lockedApplication->documents()->create([
                        'uploaded_by' => $applicant->id,
                        'path' => $path,
                        'mime_type' => $mimeType,
                    ]);
                }

                $lockedApplication->status = MembershipApplication::UNDER_REVIEW;
                $lockedApplication->review_reason = null;
                $lockedApplication->save();

                $lockedApplication->events()->create([
                    'actor_id' => $applicant->id,
                    'event' => 'additional_documents_submitted',
                    'from_status' => MembershipApplication::DOCUMENTS_REQUIRED,
                    'to_status' => MembershipApplication::UNDER_REVIEW,
                ]);

                return $lockedApplication;
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete(array_column($paths, 0));

            throw $exception;
        }
    }
}
