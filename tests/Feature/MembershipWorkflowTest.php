<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipApplicationDocument;
use App\Models\User;
use Database\Seeders\DemoMemberSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MembershipWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_a_pending_application_without_member_access(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Applicant Example',
            'email' => 'applicant@example.com',
            'phone' => '078-540530',
            'address' => 'Kawasoti -2 BP Chowk',
            'password' => 'correct horse battery staple',
            'password_confirmation' => 'correct horse battery staple',
        ]);

        $response->assertRedirect(route('membership.application'));

        $user = User::query()->where('email', 'applicant@example.com')->firstOrFail();
        $application = $user->membershipApplications()->firstOrFail();
        $this->assertSame(MembershipApplication::PENDING, $application->status);
        $this->assertFalse($user->is_admin);
        $this->assertDatabaseMissing('members', ['user_id' => $user->id]);

        $this->get(route('dashboard'))
            ->assertRedirect(route('membership.application'));
    }

    public function test_public_member_directory_lists_only_active_names_and_paginates(): void
    {
        for ($number = 1; $number <= 21; $number++) {
            $this->createDirectoryMember(
                sprintf('Directory Member %02d', $number),
                sprintf('PRIVATE-MBR-%03d', $number),
                'active',
                $number,
            );
        }

        $this->createDirectoryMember('Suspended Directory Member', 'PRIVATE-MBR-999', 'suspended', 22);

        $firstPage = $this->get(route('members.index'));

        $firstPage->assertOk()
            ->assertSee('Member Directory')
            ->assertSee('Showing 20 of 21 members')
            ->assertSee('Directory Member 21')
            ->assertSee('Directory Member 02')
            ->assertDontSee('Directory Member 01')
            ->assertDontSee('Suspended Directory Member')
            ->assertDontSee('PRIVATE-MBR-021')
            ->assertSee(route('members.index', ['page' => 2]), false);

        $secondPage = $this->get(route('members.index', ['page' => 2]));

        $secondPage->assertOk()
            ->assertSee('Showing 1 of 21 members')
            ->assertSee('Directory Member 01')
            ->assertDontSee('Directory Member 21')
            ->assertDontSee('Suspended Directory Member');
    }

    public function test_public_member_directory_escapes_member_names(): void
    {
        $this->createDirectoryMember('<script>alert("name")</script>', 'PRIVATE-MBR-XSS', 'active', 1);

        $this->get(route('members.index'))
            ->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert("name")</script>', false)
            ->assertDontSee('PRIVATE-MBR-XSS');
    }

    public function test_active_member_number_is_revealed_only_after_requesting_it(): void
    {
        $this->createDirectoryMember('Number Reveal Member', 'PRIVATE-MBR-321', 'active', 1);
        $member = Member::query()->where('member_number', 'PRIVATE-MBR-321')->firstOrFail();

        $this->get(route('members.index'))
            ->assertOk()
            ->assertDontSee('PRIVATE-MBR-321')
            ->assertSee(route('members.number', $member->id), false)
            ->assertSee('aria-label="Show member number"', false);

        $this->getJson(route('members.number', $member))
            ->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private')
            ->assertExactJson(['member_number' => 'PRIVATE-MBR-321']);
    }

    public function test_suspended_member_number_cannot_be_revealed(): void
    {
        $this->createDirectoryMember('Suspended Number Member', 'PRIVATE-MBR-654', 'suspended', 1);
        $member = Member::query()->where('member_number', 'PRIVATE-MBR-654')->firstOrFail();

        $this->getJson(route('members.number', $member))->assertNotFound();
    }

    public function test_homepage_displays_ten_member_names_after_seeding(): void
    {
        $this->createDirectoryMember('Registered Directory Member', 'PRIVATE-MBR-001', 'active', 1);

        $this->seed(DemoMemberSeeder::class);
        $this->seed(DemoMemberSeeder::class);

        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Showing 10 of 10 members')
            ->assertSee('Registered Directory Member')
            ->assertSee('Suman Adhikari')
            ->assertSee('Nabin Khadka')
            ->assertDontSee('Demo Member')
            ->assertDontSee('(Sample)')
            ->assertDontSee('PRIVATE-MBR-001')
            ->assertDontSee('SAMPLE-MBR-002');

        $this->assertSame(10, substr_count($response->getContent(), 'data-member-preview-row'));
        $this->assertDatabaseCount('members', 10);
    }

    public function test_admin_approval_creates_a_member_and_records_the_reviewer(): void
    {
        $admin = $this->administrator();
        $applicant = User::factory()->create();
        $application = $applicant->membershipApplications()->create([
            'phone' => '078-540530',
            'address' => 'Kawasoti',
            'status' => MembershipApplication::PENDING,
            'submitted_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.applications.review', [
                'membershipApplication' => $application,
                'decision' => 'approve',
            ]))
            ->assertRedirect(route('admin.applications.show', $application));

        $this->assertDatabaseHas('members', [
            'user_id' => $applicant->id,
            'membership_application_id' => $application->id,
            'member_number' => 'MBR-000001',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('membership_applications', [
            'id' => $application->id,
            'status' => MembershipApplication::APPROVED,
            'reviewed_by' => $admin->id,
        ]);
        $this->assertDatabaseHas('membership_application_events', [
            'membership_application_id' => $application->id,
            'actor_id' => $admin->id,
            'event' => 'application_approved',
            'from_status' => MembershipApplication::PENDING,
            'to_status' => MembershipApplication::APPROVED,
        ]);

        $this->actingAs($applicant)
            ->get(route('dashboard'))
            ->assertOk();
    }

    public function test_rejection_requires_a_reason_and_does_not_create_a_member(): void
    {
        $admin = $this->administrator();
        $applicant = User::factory()->create();
        $application = $applicant->membershipApplications()->create([
            'phone' => '078-540530',
            'address' => 'Kawasoti',
            'status' => MembershipApplication::PENDING,
            'submitted_at' => now(),
        ]);

        $this->actingAs($admin)
            ->from(route('admin.applications.show', $application))
            ->post(route('admin.applications.review', [
                'membershipApplication' => $application,
                'decision' => 'reject',
            ]))
            ->assertSessionHasErrors('note');

        $this->assertDatabaseHas('membership_applications', [
            'id' => $application->id,
            'status' => MembershipApplication::PENDING,
        ]);
        $this->assertDatabaseMissing('members', ['user_id' => $applicant->id]);
    }

    public function test_requested_documents_can_be_submitted_privately_by_the_applicant(): void
    {
        Storage::fake('local');

        $admin = $this->administrator();
        $applicant = User::factory()->create();
        $application = $applicant->membershipApplications()->create([
            'phone' => '078-540530',
            'address' => 'Kawasoti',
            'status' => MembershipApplication::PENDING,
            'submitted_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.applications.review', [
                'membershipApplication' => $application,
                'decision' => 'request_documents',
            ]), ['note' => 'Please provide one supporting document.'])
            ->assertRedirect(route('admin.applications.show', $application));

        $document = UploadedFile::fake()->create('supporting.pdf', 20, 'application/pdf');

        $this->actingAs($applicant)
            ->post(route('membership.application.documents.store'), [
                'documents' => [$document],
            ])
            ->assertRedirect(route('membership.application'));

        $storedDocument = MembershipApplicationDocument::query()->firstOrFail();
        Storage::disk('local')->assertExists($storedDocument->path);
        $this->assertDatabaseHas('membership_applications', [
            'id' => $application->id,
            'status' => MembershipApplication::UNDER_REVIEW,
        ]);
    }

    public function test_regular_account_cannot_view_membership_applications_in_admin(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.applications.index'))
            ->assertForbidden();
    }

    public function test_member_status_changes_are_audited_and_suspended_members_are_blocked(): void
    {
        $admin = $this->administrator();
        $memberUser = User::factory()->create();
        $application = $memberUser->membershipApplications()->create([
            'phone' => '078-540530',
            'address' => 'Kawasoti',
            'status' => MembershipApplication::APPROVED,
            'submitted_at' => now(),
        ]);
        $member = $memberUser->member()->create([
            'membership_application_id' => $application->id,
            'member_number' => 'MBR-000001',
            'phone' => '078-540530',
            'address' => 'Kawasoti',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.members.status', $member), [
                'status' => 'suspended',
                'reason' => 'Membership under review.',
            ])
            ->assertRedirect(route('admin.members.show', $member));

        $this->assertDatabaseHas('membership_application_events', [
            'membership_application_id' => $application->id,
            'actor_id' => $admin->id,
            'event' => 'member_status_changed',
            'from_status' => 'active',
            'to_status' => 'suspended',
            'note' => 'Membership under review.',
        ]);

        $this->actingAs($memberUser)
            ->get(route('dashboard'))
            ->assertRedirect(route('membership.application'));
    }

    public function test_member_contact_updates_are_recorded_in_membership_history(): void
    {
        $admin = $this->administrator();
        $memberUser = User::factory()->create();
        $application = $memberUser->membershipApplications()->create([
            'phone' => '078-540530',
            'address' => 'Kawasoti',
            'status' => MembershipApplication::APPROVED,
            'submitted_at' => now(),
        ]);
        $member = $memberUser->member()->create([
            'membership_application_id' => $application->id,
            'member_number' => 'MBR-000001',
            'phone' => '078-540530',
            'address' => 'Kawasoti',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $this->actingAs($admin)
            ->put(route('admin.members.update', $member), [
                'phone' => '078-999999',
                'address' => 'Nawalpur',
            ])
            ->assertRedirect(route('admin.members.show', $member));

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'phone' => '078-999999',
            'address' => 'Nawalpur',
        ]);
        $this->assertDatabaseHas('membership_application_events', [
            'membership_application_id' => $application->id,
            'actor_id' => $admin->id,
            'event' => 'member_details_updated',
            'note' => 'Updated fields: phone, address',
        ]);
    }

    public function test_staff_can_open_application_and_member_records(): void
    {
        $admin = $this->administrator();
        $applicant = User::factory()->create();
        $application = $applicant->membershipApplications()->create([
            'phone' => '078-540530',
            'address' => 'Kawasoti',
            'status' => MembershipApplication::APPROVED,
            'submitted_at' => now(),
        ]);
        $member = $applicant->member()->create([
            'membership_application_id' => $application->id,
            'member_number' => 'MBR-000001',
            'phone' => '078-540530',
            'address' => 'Kawasoti',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.applications.index'))
            ->assertOk()
            ->assertSee($applicant->email);

        $this->get(route('admin.applications.show', $application))
            ->assertOk()
            ->assertSee('Applicant information');

        $this->get(route('admin.members.index'))
            ->assertOk()
            ->assertSee($member->member_number);

        $this->get(route('admin.members.show', $member))
            ->assertOk()
            ->assertSee('Member details');
    }

    public function test_private_application_document_download_requires_staff_access(): void
    {
        Storage::fake('local');

        $applicant = User::factory()->create();
        $application = $applicant->membershipApplications()->create([
            'phone' => '078-540530',
            'address' => 'Kawasoti',
            'status' => MembershipApplication::PENDING,
            'submitted_at' => now(),
        ]);
        $path = 'membership-applications/identity.pdf';
        Storage::disk('local')->put($path, 'private test document');
        $document = $application->documents()->create([
            'uploaded_by' => $applicant->id,
            'path' => $path,
            'mime_type' => 'application/pdf',
        ]);

        $this->actingAs($applicant)
            ->get(route('admin.applications.documents.download', $document))
            ->assertForbidden();

        $this->actingAs($this->administrator())
            ->get(route('admin.applications.documents.download', $document))
            ->assertOk();
    }

    private function administrator(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        return $admin;
    }

    private function createDirectoryMember(string $name, string $memberNumber, string $status, int $joinedAtOffset): void
    {
        $user = User::factory()->create(['name' => $name]);
        $application = $user->membershipApplications()->create([
            'phone' => '078-540530',
            'address' => 'Private address',
            'status' => MembershipApplication::APPROVED,
            'submitted_at' => now(),
        ]);
        $user->member()->create([
            'membership_application_id' => $application->id,
            'member_number' => $memberNumber,
            'phone' => '078-540530',
            'address' => 'Private address',
            'status' => $status,
            'joined_at' => now()->addSeconds($joinedAtOffset),
        ]);
    }
}
