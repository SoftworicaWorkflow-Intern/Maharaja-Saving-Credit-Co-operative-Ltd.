<?php

namespace Tests\Feature;

use App\Models\Notice;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoticeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_only_active_published_notices_in_the_local_language_ticker(): void
    {
        $this->freezeTime();

        $publishedNotice = $this->createNotice([
            'title_en' => 'Office <script>alert("unsafe")</script> hours update',
            'title_np' => 'कार्यालय समय परिवर्तन',
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);
        $this->createNotice([
            'title_en' => 'Member services update',
            'title_np' => 'सदस्य सेवा अद्यावधिक',
            'status' => 'published',
            'published_at' => null,
        ]);
        $this->createNotice([
            'title_en' => 'Draft announcement',
            'title_np' => 'मस्यौदा सूचना',
            'status' => 'draft',
        ]);
        $this->createNotice([
            'title_en' => 'Scheduled announcement',
            'title_np' => 'अनुसूचित सूचना',
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);

        $this->withSession(['locale' => 'ne'])
            ->get(route('home'))
            ->assertOk()
            ->assertSee('data-news-ticker', false)
            ->assertSee('कार्यालय समय परिवर्तन')
            ->assertSee('सदस्य सेवा अद्यावधिक')
            ->assertDontSee('Draft announcement')
            ->assertDontSee('Scheduled announcement')
            ->assertSee(route('notices.show', $publishedNotice), false);

        $this->withSession(['locale' => 'en'])
            ->get(route('home'))
            ->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>', false)
            ->assertSee('Member services update');
    }

    public function test_homepage_hides_ticker_when_no_notice_is_publicly_active(): void
    {
        $this->createNotice([
            'title_en' => 'Draft only',
            'title_np' => 'मस्यौदा मात्र',
            'status' => 'draft',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('data-news-ticker', false);
    }

    public function test_public_notice_detail_is_available_only_after_publication(): void
    {
        $this->freezeTime();

        $publishedNotice = $this->createNotice([
            'title_en' => 'Member meeting',
            'title_np' => 'सदस्य बैठक',
            'body_en' => '<script>alert("unsafe")</script>Meeting details.',
            'body_np' => '<script>alert("unsafe")</script>बैठकको विवरण।',
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);
        $draftNotice = $this->createNotice([
            'title_en' => 'Unpublished meeting',
            'title_np' => 'अप्रकाशित बैठक',
            'status' => 'draft',
        ]);

        $this->withSession(['locale' => 'ne'])
            ->get(route('notices.show', $publishedNotice))
            ->assertOk()
            ->assertSee('सदस्य बैठक')
            ->assertSee('बैठकको विवरण।')
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>', false);

        $this->withSession(['locale' => 'en'])
            ->get(route('notices.show', $publishedNotice))
            ->assertOk()
            ->assertSee('Member meeting')
            ->assertSee('Meeting details.')
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>', false);

        $this->get(route('notices.show', $draftNotice))
            ->assertNotFound();

        $scheduledNotice = $this->createNotice([
            'title_en' => 'Future meeting',
            'title_np' => 'भविष्यको बैठक',
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('notices.show', $scheduledNotice))
            ->assertNotFound();
    }

    public function test_admin_can_create_publish_unpublish_and_delete_notices(): void
    {
        $this->freezeTime();

        $administrator = $this->administrator();
        $noticeData = [
            'title_en' => 'Branch service update',
            'title_np' => 'शाखा सेवा सूचना',
            'body_en' => 'Updated branch service information.',
            'body_np' => 'शाखा सेवासम्बन्धी अद्यावधिक जानकारी।',
            'category' => 'general',
            'status' => 'draft',
            'featured' => '1',
        ];

        $this->actingAs($administrator)
            ->get(route('admin.notices.index'))
            ->assertOk()
            ->assertSee('Notices & announcements', false);

        $this->get(route('admin.notices.create'))
            ->assertOk()
            ->assertSee('English title');

        $this->post(route('admin.notices.store'), $noticeData)
            ->assertRedirect();

        $notice = Notice::query()->where('title_en', 'Branch service update')->firstOrFail();
        $this->assertSame('draft', $notice->status);
        $this->assertTrue($notice->featured);

        $this->get(route('admin.notices.edit', $notice))
            ->assertOk()
            ->assertSee('Save changes');

        $this->put(route('admin.notices.update', $notice), [
            ...$noticeData,
            'status' => 'published',
            'featured' => '0',
        ])
            ->assertRedirect(route('admin.notices.edit', $notice));

        $notice->refresh();
        $this->assertSame('published', $notice->status);
        $this->assertNotNull($notice->published_at);
        $this->assertFalse($notice->featured);

        $this->get(route('home'))
            ->assertSee('Branch service update');

        $this->put(route('admin.notices.update', $notice), [
            ...$noticeData,
            'status' => 'draft',
        ])
            ->assertRedirect(route('admin.notices.edit', $notice));

        $this->get(route('home'))
            ->assertDontSee('data-news-ticker', false);

        $this->delete(route('admin.notices.destroy', $notice))
            ->assertRedirect(route('admin.notices.index'));

        $this->assertDatabaseMissing('notices', ['id' => $notice->id]);
    }

    public function test_notice_management_requires_the_notices_permission(): void
    {
        $this->seed();
        $role = Role::query()->where('slug', 'content_editor')->firstOrFail();
        $role->permissions()->attach(
            Permission::query()->where('slug', 'admin.access')->firstOrFail(),
        );
        $staffMember = User::factory()->create();
        $staffMember->roles()->attach($role);

        $this->get(route('admin.notices.index'))
            ->assertRedirect(route('login'));

        $this->actingAs($staffMember)
            ->get(route('admin.notices.index'))
            ->assertForbidden();
    }

    public function test_invalid_notice_data_is_rejected_without_creating_a_record(): void
    {
        $this->actingAs($this->administrator())
            ->post(route('admin.notices.store'), [
                'title_en' => '',
                'title_np' => '',
                'category' => 'unrecognized',
                'status' => 'archived',
            ])
            ->assertSessionHasErrors(['title_en', 'title_np', 'category', 'status']);

        $this->assertDatabaseCount('notices', 0);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createNotice(array $attributes = []): Notice
    {
        return Notice::query()->create([
            'title_en' => 'Cooperative update',
            'title_np' => 'सहकारी सूचना',
            'body_en' => 'Notice details.',
            'body_np' => 'सूचना विवरण।',
            'category' => 'general',
            'status' => 'published',
            'published_at' => now()->subMinute(),
            ...$attributes,
        ]);
    }

    private function administrator(): User
    {
        $administrator = User::factory()->create();
        $administrator->forceFill(['is_admin' => true])->save();

        return $administrator;
    }
}
