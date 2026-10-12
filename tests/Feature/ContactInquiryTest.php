<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactInquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_places_the_message_form_before_the_map(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSeeInOrder(['Office information', 'Send a message', 'Find our office']);
    }

    public function test_public_contact_form_saves_a_message_for_staff_review(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'Example Visitor',
            'email' => 'visitor@example.com',
            'phone' => '078-540530',
            'subject' => 'Office hours',
            'message' => 'What time does the office close?',
        ]);

        $response->assertRedirect(route('contact'))
            ->assertSessionHas('status', 'Thank you. Your message has been sent to the cooperative.');

        $this->assertDatabaseHas('contact_inquiries', [
            'name' => 'Example Visitor',
            'email' => 'visitor@example.com',
            'phone' => '078-540530',
            'subject' => 'Office hours',
            'message' => 'What time does the office close?',
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);
    }

    public function test_contact_form_validates_required_fields_without_saving(): void
    {
        $this->from(route('contact'))
            ->post(route('contact.submit'), [])
            ->assertSessionHasErrors(['name', 'email', 'message']);

        $this->assertDatabaseCount('contact_inquiries', 0);
    }

    public function test_only_staff_can_read_and_mark_inquiries_reviewed(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $inquiry = ContactInquiry::query()->create([
            'name' => 'Example Visitor',
            'email' => 'visitor@example.com',
            'subject' => 'Office hours',
            'message' => 'What time does the office close?',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.contact-inquiries.index'))
            ->assertOk()
            ->assertSee($inquiry->email);

        $this->get(route('admin.contact-inquiries.show', $inquiry))
            ->assertOk()
            ->assertSee('What time does the office close?');

        $this->patch(route('admin.contact-inquiries.reviewed', $inquiry))
            ->assertRedirect(route('admin.contact-inquiries.show', $inquiry));

        $this->assertDatabaseHas('contact_inquiries', [
            'id' => $inquiry->id,
            'reviewed_by' => $admin->id,
        ]);
        $this->assertNotNull($inquiry->fresh()->reviewed_at);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.contact-inquiries.index'))
            ->assertForbidden();
    }

    public function test_staff_contact_view_escapes_untrusted_message_html(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $message = '<script>alert("xss")</script>';
        $inquiry = ContactInquiry::query()->create([
            'name' => 'Example Visitor',
            'email' => 'visitor@example.com',
            'message' => $message,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.contact-inquiries.show', $inquiry))
            ->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee($message, false);
    }
}
