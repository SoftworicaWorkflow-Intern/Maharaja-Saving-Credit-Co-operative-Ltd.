<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewMembershipApplicationRequest;
use App\Http\Requests\StoreAdditionalDocumentsRequest;
use App\Models\MembershipApplication;
use App\Models\MembershipApplicationDocument;
use App\Services\MembershipApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MembershipApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(MembershipApplication::OPEN_STATUSES + [
                MembershipApplication::APPROVED,
                MembershipApplication::REJECTED,
            ])],
        ]);

        $query = MembershipApplication::query()
            ->with('user:id,name,email')
            ->withCount('documents');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($query) use ($search): void {
                $query->where('phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($query) use ($search): void {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $applications = $query
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.applications.index', compact('applications', 'filters'));
    }

    public function show(MembershipApplication $membershipApplication): View
    {
        $membershipApplication->load([
            'user:id,name,email',
            'reviewer:id,name',
            'documents.uploader:id,name',
            'events' => fn ($query) => $query->with('actor:id,name')->orderBy('created_at'),
        ]);

        return view('admin.applications.show', [
            'application' => $membershipApplication,
        ]);
    }

    public function review(
        ReviewMembershipApplicationRequest $request,
        MembershipApplication $membershipApplication,
        string $decision,
        MembershipApplicationService $applications,
    ) {
        $applications->review(
            $membershipApplication,
            $request->user(),
            $decision,
            $request->validated('note'),
        );

        return redirect()
            ->route('admin.applications.show', $membershipApplication)
            ->with('status', 'Application review recorded.');
    }

    public function downloadDocument(MembershipApplicationDocument $document): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($document->path), 404);

        return Storage::disk('local')->download($document->path);
    }

    public function storeAdditionalDocuments(
        StoreAdditionalDocumentsRequest $request,
        MembershipApplicationService $applications,
    ) {
        $application = $request->user()
            ->membershipApplications()
            ->whereIn('status', [MembershipApplication::DOCUMENTS_REQUIRED])
            ->latest('submitted_at')
            ->firstOrFail();

        $applications->submitAdditionalDocuments(
            $application,
            $request->user(),
            $request->file('documents'),
        );

        return redirect()
            ->route('membership.application')
            ->with('status', 'Your additional documents have been submitted.');
    }
}
