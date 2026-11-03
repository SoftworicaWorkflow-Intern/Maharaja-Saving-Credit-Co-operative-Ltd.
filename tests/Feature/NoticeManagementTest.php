<?php

namespace Tests\Feature;

use App\Models\Notice;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
            ->assertDontSee('<script>alert("unsafe")</script>', false)
            ->assertSee('Member services update');
    }

    public function test_homepage_labels_the_published_notice_ticker_as_latest_news(): void
    {
        $this->createNotice([
            'title_en' => 'Community update',
            'title_np' => 'समुदाय अद्यावधिक',
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);

        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertSee('data-news-ticker', false)
            ->assertSee('LATEST NEWS')
            ->assertSee('Community update');

        $this->assertLessThan(
            strpos($response->getContent(), 'data-hero-carousel'),
            strpos($response->getContent(), 'data-news-ticker'),
        );
    }

    public function test_homepage_shows_latest_news_bar_without_published_notices(): void
    {
        $this->createNotice([
            'title_en' => 'Draft only',
            'title_np' => 'मस्यौदा मात्र',
            'status' => 'draft',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('data-news-ticker', false)
            ->assertSee('No latest news is available right now.');
    }

    public function test_notice_listing_shows_search_and_filter_controls_and_notice_categories(): void
    {
        $this->createNotice([
            'title_en' => 'General member update',
            'category' => 'general',
        ]);

        $this->get(route('notices'))
            ->assertOk()
            ->assertSee('General member update')
            ->assertSee('>General</span>', false)
            ->assertSee('समाचार तथा सूचनाहरू')
            ->assertSee('Notices')
            ->assertSee('lg:grid-cols-3', false)
            ->assertSee('data-notice-preview', false)
            ->assertSee('Read full notice')
            ->assertSee('name="search"', false)
            ->assertSee('name="category"', false)
            ->assertSee('name="published_on"', false)
            ->assertSee('All categories')
            ->assertSee('>Search</button>', false);
    }

    public function test_notice_listing_filters_by_search_category_and_publication_date(): void
    {
        $this->freezeTime();
        $matchedNotice = $this->createNotice([
            'title_en' => 'Member dividend statement',
            'body_en' => 'Annual allocation information for members.',
            'category' => 'financial',
            'published_at' => now()->subDay(),
        ]);
        $otherNotice = $this->createNotice([
            'title_en' => 'General office update',
            'body_en' => 'Office service schedule information.',
            'category' => 'general',
            'published_at' => now()->subDays(2),
        ]);

        $this->get(route('notices', [
            'search' => 'allocation',
            'category' => 'financial',
            'published_on' => now()->subDay()->toDateString(),
        ]))
            ->assertOk()
            ->assertSee($matchedNotice->title_en)
            ->assertDontSee($otherNotice->title_en);
    }

    public function test_notice_listing_shows_nine_notices_per_page(): void
    {
        $this->freezeTime();

        for ($noticeNumber = 1; $noticeNumber <= 10; $noticeNumber++) {
            $this->createNotice([
                'title_en' => "Published notice {$noticeNumber}",
                'published_at' => now()->subMinutes($noticeNumber),
            ]);
        }

        $firstPage = $this->get(route('notices'))
            ->assertOk()
            ->assertSee('page=2', false)
            ->assertDontSee('>Published notice 10</a>', false);

        for ($noticeNumber = 1; $noticeNumber <= 9; $noticeNumber++) {
            $firstPage->assertSee("Published notice {$noticeNumber}");
        }

        $this->get(route('notices', ['page' => 2]))
            ->assertOk()
            ->assertSee('Published notice 10')
            ->assertDontSee('>Published notice 1</a>', false);
    }

    public function test_public_notice_documents_support_inline_preview_download_and_publication_checks(): void
    {
        Storage::fake('local');
        $imagePath = 'notices/member-update.png';
        $pdfPath = 'notices/annual-update.pdf';
        Storage::disk('local')->put($imagePath, file_get_contents(public_path('images/logo.png')));
        Storage::disk('local')->put($pdfPath, '%PDF-1.4 test document');

        $imageNotice = $this->createNotice([
            'title_en' => 'Published image notice',
            'document_path' => $imagePath,
            'document_mime_type' => 'image/png',
        ]);
        $pdfNotice = $this->createNotice([
            'title_en' => 'Published PDF notice',
            'document_path' => $pdfPath,
            'document_mime_type' => 'application/pdf',
        ]);
        $draftNotice = $this->createNotice([
            'title_en' => 'Draft with a document',
            'status' => 'draft',
            'document_path' => $imagePath,
            'document_mime_type' => 'image/png',
        ]);

        $this->get(route('notices.document', $imageNotice))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png')
            ->assertHeader('X-Content-Type-Options', 'nosniff');

        $this->get(route('notices.download', $pdfNotice))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertHeader('X-Content-Type-Options', 'nosniff');

        $this->get(route('notices.show', $imageNotice))
            ->assertOk()
            ->assertSee(route('notices.document', $imageNotice), false)
            ->assertSee(route('notices.download', $imageNotice), false)
            ->assertSee('Back to Notices')
            ->assertSee('Print notice');

        $this->get(route('notices.show', $pdfNotice))
            ->assertOk()
            ->assertSee('<iframe src="'.route('notices.document', $pdfNotice).'"', false);

        $this->get(route('notices.document', $draftNotice))->assertNotFound();
        $this->get(route('notices.download', $draftNotice))->assertNotFound();
    }

    public function test_homepage_notice_cards_link_to_the_full_notice(): void
    {
        $notices = collect([
            $this->createNotice([
                'title_en' => 'Annual member meeting',
                'body_en' => 'Full details about the annual member meeting and its agenda.',
            ]),
            $this->createNotice([
                'title_en' => 'Savings scheme update',
                'body_en' => 'Full details about the updated savings scheme.',
                'category' => 'savings',
            ]),
            $this->createNotice([
                'title_en' => 'Loan service announcement',
                'body_en' => 'Full details about loan service availability.',
                'category' => 'loan',
            ]),
        ]);

        $response = $this->get(route('home'))
            ->assertOk()
            ->assertSee('Read Full Notice');

        foreach ($notices as $notice) {
            $response
                ->assertSee('<a lang="en" href="'.route('notices.show', $notice).'"', false)
                ->assertSee($notice->title_en);

            $this->get(route('notices.show', $notice))
                ->assertOk()
                ->assertSee($notice->body_en);
        }
    }

    public function test_notice_detail_shows_related_notices_from_its_category(): void
    {
        $notice = $this->createNotice([
            'title_en' => 'Current meeting notice',
            'category' => 'general',
        ]);
        $relatedNotice = $this->createNotice([
            'title_en' => 'Related meeting reminder',
            'category' => 'general',
        ]);
        $this->createNotice([
            'title_en' => 'Different savings notice',
            'category' => 'savings',
        ]);
        $this->createNotice([
            'title_en' => 'Draft meeting notice',
            'category' => 'general',
            'status' => 'draft',
        ]);

        $this->get(route('notices.show', $notice))
            ->assertOk()
            ->assertSee('Related notices')
            ->assertSee('Related meeting reminder')
            ->assertSee(route('notices.show', $relatedNotice), false)
            ->assertDontSee('Different savings notice')
            ->assertDontSee('Draft meeting notice');
    }

    public function test_notice_detail_shows_context_specific_guidance_for_savings_loans_and_membership(): void
    {
        $savingsNotice = $this->createNotice([
            'title_en' => 'Maintain a regular savings habit',
            'category' => 'savings',
        ]);
        $this->get(route('notices.show', $savingsNotice))
            ->assertOk()
            ->assertSee('Savings planning')
            ->assertSee('Choose a deposit amount and schedule that fit your budget and savings goal.')
            ->assertSee(route('contact'), false);

        $loanNotice = $this->createNotice([
            'title_en' => 'Plan carefully before borrowing',
            'category' => 'loan',
        ]);
        $this->get(route('notices.show', $loanNotice))
            ->assertOk()
            ->assertSee('Borrowing responsibly')
            ->assertSee('Confirm the current interest rate and total repayment before accepting an offer.');

        $membershipNotice = $this->createNotice([
            'title_en' => 'Keep your membership details up to date',
            'category' => 'general',
        ]);
        $this->get(route('notices.show', $membershipNotice))
            ->assertOk()
            ->assertSee('Keeping your membership details current')
            ->assertSee('Confirm the changes have been recorded so future notices reach you.');
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
        $this->withoutMiddleware(PreventRequestForgery::class);

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

        $this->withoutMiddleware(PreventRequestForgery::class)
            ->actingAs($administrator)
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

    public function test_admin_can_upload_and_remove_a_notice_document(): void
    {
        Storage::fake('local');
        $administrator = $this->administrator();
        $document = new UploadedFile(public_path('images/logo.png'), 'member-notice.png', 'image/png', UPLOAD_ERR_OK, true);
        $noticeData = [
            'title_en' => 'Member notice',
            'title_np' => 'सदस्य सूचना',
            'body_en' => 'Notice details.',
            'body_np' => 'सूचना विवरण।',
            'category' => 'member',
            'status' => 'draft',
            'document' => $document,
        ];
        $csrfToken = csrf_token();

        $this->withoutMiddleware(PreventRequestForgery::class)
            ->actingAs($administrator)
            ->withSession(['_token' => $csrfToken])
            ->post(route('admin.notices.store'), ['_token' => $csrfToken, ...$noticeData])
            ->assertRedirect();

        $notice = Notice::query()->where('title_en', 'Member notice')->firstOrFail();
        $documentPath = $notice->document_path;
        $this->assertSame('image/png', $notice->document_mime_type);
        Storage::disk('local')->assertExists($documentPath);

        $this->actingAs($administrator)
            ->get(route('admin.notices.edit', $notice))
            ->assertOk()
            ->assertSee('Current file: PNG')
            ->assertSee('name="remove_document"', false);

        $this->actingAs($administrator)
            ->withSession(['_token' => $csrfToken])
            ->put(route('admin.notices.update', $notice), [
                '_token' => $csrfToken,
                ...$noticeData,
                'document' => null,
                'remove_document' => '1',
            ])
            ->assertRedirect(route('admin.notices.edit', $notice));

        $notice->refresh();
        $this->assertNull($notice->document_path);
        $this->assertNull($notice->document_mime_type);
        Storage::disk('local')->assertMissing($documentPath);
    }

    public function test_admin_cannot_upload_an_unsupported_notice_document(): void
    {
        Storage::fake('local');
        $csrfToken = csrf_token();

        $this->withoutMiddleware(PreventRequestForgery::class)
            ->actingAs($this->administrator())
            ->withSession(['_token' => $csrfToken])
            ->post(route('admin.notices.store'), [
                '_token' => $csrfToken,
                'title_en' => 'Unsupported attachment',
                'title_np' => 'असमर्थित फाइल',
                'category' => 'general',
                'status' => 'draft',
                'document' => UploadedFile::fake()->createWithContent('notice.txt', 'Not an image or PDF.'),
            ])
            ->assertSessionHasErrors('document');

        $this->assertDatabaseCount('notices', 0);
        $this->assertSame([], Storage::disk('local')->allFiles('notices'));
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
        $this->withoutMiddleware(PreventRequestForgery::class);

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
