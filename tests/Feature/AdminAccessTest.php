<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use App\Models\MembershipApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_member_is_forbidden_from_admin_dashboard(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_access_dashboard_without_fabricated_financial_totals(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewIs('admin.dashboard')
            ->assertSee('No financial totals are shown until verified records and authorized workflows are available.');
    }

    public function test_admin_dashboard_shows_permission_aware_operational_counts(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $applicant = User::factory()->create();
        $applicant->membershipApplications()->create([
            'phone' => '078-540530',
            'address' => 'Kawasoti',
            'status' => MembershipApplication::PENDING,
            'submitted_at' => now(),
        ]);
        $applicant->membershipApplications()->create([
            'phone' => '078-540531',
            'address' => 'Kawasoti',
            'status' => MembershipApplication::UNDER_REVIEW,
            'submitted_at' => now(),
        ]);
        $memberUser = User::factory()->create();
        $approvedApplication = $memberUser->membershipApplications()->create([
            'phone' => '078-540532',
            'address' => 'Kawasoti',
            'status' => MembershipApplication::APPROVED,
            'submitted_at' => now(),
        ]);
        $memberUser->member()->create([
            'membership_application_id' => $approvedApplication->id,
            'member_number' => 'MBR-000001',
            'phone' => '078-540532',
            'address' => 'Kawasoti',
            'status' => 'active',
            'joined_at' => now(),
        ]);
        ContactInquiry::query()->create([
            'name' => 'Example Visitor',
            'email' => 'visitor@example.com',
            'message' => 'Please contact me.',
        ]);
        ContactInquiry::query()->create([
            'name' => 'Reviewed Visitor',
            'email' => 'reviewed@example.com',
            'message' => 'This message is already reviewed.',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSeeInOrder([
                'Open membership applications',
                '2',
                'Active members',
                '1',
                'Unreviewed inquiries',
                '1',
            ])
            ->assertSee('Quick access')
            ->assertSee('Membership applications');
    }

    public function test_public_registration_cannot_grant_administrator_access(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'New Member',
            'email' => 'new-member@example.com',
            'phone' => '078-540530',
            'address' => 'Kawasoti',
            'password' => 'correct horse battery staple',
            'password_confirmation' => 'correct horse battery staple',
            'is_admin' => true,
        ]);

        $response->assertRedirect(route('membership.application'));

        $member = User::query()->where('email', 'new-member@example.com')->firstOrFail();
        $this->assertFalse($member->is_admin);

        $this->actingAs($member)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_supported_locale_can_be_selected_and_applied_to_admin_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $this->actingAs($admin)
            ->from(route('admin.dashboard'))
            ->post(route('language.switch', ['locale' => 'ne']))
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('locale', 'ne');

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('ड्यासबोर्ड');
    }

    public function test_unsupported_locale_is_not_accepted(): void
    {
        $this->from(route('home'))
            ->post(route('language.switch', ['locale' => 'fr']))
            ->assertNotFound();
    }
}
