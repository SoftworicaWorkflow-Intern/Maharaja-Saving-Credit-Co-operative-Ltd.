<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LeadershipMessagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_displays_alternating_leadership_previews_before_the_trust_section(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertSeeInOrder([
                'LEADERSHIP MESSAGES',
                'Words from Our Leadership',
                'Portrait not provided for Chairperson',
                'CHAIRPERSON&#039;S MESSAGE',
                'Building a Prosperous Future Together',
                'Portrait not provided for Secretary',
                'SECRETARY&#039;S MESSAGE',
                'Growing Together Through Trust and Cooperation',
                'Why members trust us',
            ], false);

        $this->assertSame(2, substr_count($response->getContent(), 'Read Full Message'));
        $response->assertSee('order-1 lg:order-2', false)
            ->assertSee('order-2 lg:order-1', false);
    }

    public function test_full_messages_keep_the_supplied_report_text_and_can_be_printed(): void
    {
        $this->get(route('leadership.show', 'chairperson'))
            ->assertOk()
            ->assertSee('It is my privilege to share the annual progress report')
            ->assertSee('धन्यवाद। जय सहकारी! जय महाराजा!')
            ->assertSee('data-print-page', false)
            ->assertSee('Print Message');

        $this->get(route('leadership.show', 'secretary'))
            ->assertOk()
            ->assertSee('Established in 2067 B.S.')
            ->assertSee('धन्यवाद। जय सहकारी! जय महाराजा!');
    }

    public function test_full_leadership_message_uses_nepali_after_language_selection(): void
    {
        $this->post(route('language.switch', ['locale' => 'ne']))
            ->assertRedirect();

        $this->get(route('leadership.show', 'secretary'))
            ->assertOk()
            ->assertSee('सचिवको सन्देश')
            ->assertSee('विश्वास र सहकार्यबाट सँगै अघि बढ्दै')
            ->assertSee('वि.सं. २०६७ मा स्थापना भएको')
            ->assertSee('सन्देश प्रिन्ट गर्नुहोस्');
    }

    public function test_authorized_admin_can_save_separate_verified_names_and_portraits(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $this->actingAs($admin)
            ->get(route('admin.leadership.index'))
            ->assertOk()
            ->assertSee('No portrait uploaded; the public site shows a neutral placeholder.');

        $this->put(route('admin.leadership.update', 'chairperson'), [
            'name' => 'Verified Chairperson',
            'portrait' => $this->portraitUpload('chairperson.png'),
        ])->assertRedirect(route('admin.leadership.index'));

        $this->put(route('admin.leadership.update', 'secretary'), [
            'name' => 'Verified Secretary',
            'portrait' => $this->portraitUpload('secretary.png'),
        ])->assertRedirect(route('admin.leadership.index'));

        $chairpersonPortrait = Setting::value('leadership.chairperson.portrait');
        $secretaryPortrait = Setting::value('leadership.secretary.portrait');

        $this->assertSame('Verified Chairperson', Setting::value('leadership.chairperson.name'));
        $this->assertSame('Verified Secretary', Setting::value('leadership.secretary.name'));
        $this->assertNotSame($chairpersonPortrait, $secretaryPortrait);
        Storage::disk('local')->assertExists($chairpersonPortrait);
        Storage::disk('local')->assertExists($secretaryPortrait);

        $this->get(route('leadership.portrait', 'chairperson'))->assertOk();
        $this->get(route('leadership.portrait', 'secretary'))->assertOk();

        $this->get(route('home'))
            ->assertSee('Portrait of Verified Chairperson')
            ->assertSee('Portrait of Verified Secretary');
    }

    public function test_only_staff_managers_can_edit_leadership_profiles(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)
            ->get(route('admin.leadership.index'))
            ->assertForbidden();

        $this->put(route('admin.leadership.update', 'chairperson'), [
            'name' => 'Unauthorized Name',
        ])->assertForbidden();

        $this->assertDatabaseMissing('settings', [
            'key' => 'leadership.chairperson.name',
        ]);
    }

    public function test_leadership_profile_upload_rejects_non_image_files(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $this->actingAs($admin)
            ->from(route('admin.leadership.index'))
            ->put(route('admin.leadership.update', 'chairperson'), [
                'name' => 'Unchanged Name',
                'portrait' => UploadedFile::fake()->create('portrait.jpg', 1, 'text/plain'),
            ])
            ->assertRedirect(route('admin.leadership.index'))
            ->assertSessionHasErrors('portrait');

        $this->assertDatabaseMissing('settings', [
            'key' => 'leadership.chairperson.portrait',
        ]);
        $this->assertDatabaseMissing('settings', [
            'key' => 'leadership.chairperson.name',
        ]);

        Storage::disk('local')->assertDirectoryEmpty('leadership');
    }

    private function portraitUpload(string $filename): UploadedFile
    {
        $contents = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/pN8AAAAASUVORK5CYII=',
            true,
        );
        $this->assertNotFalse($contents);

        return UploadedFile::fake()->createWithContent($filename, $contents);
    }
}
