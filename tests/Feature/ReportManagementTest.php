<?php

namespace Tests\Feature;

use App\Models\FiscalYear;
use App\Models\Permission;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\ReportStatistic;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_reports_only_show_published_documents_and_support_filters(): void
    {
        $category = ReportCategory::query()->where('slug', 'annual-reports')->firstOrFail();
        $year = FiscalYear::query()->where('label', '२०८२/०८३')->firstOrFail();
        $matchingReport = $this->createReport([
            'title_en' => 'Annual audit and progress',
            'category_id' => $category->id,
            'fiscal_year_id' => $year->id,
            'status' => 'published',
            'publication_date' => today()->subDay(),
        ]);
        $this->createReport([
            'title_en' => 'A different annual report',
            'category_id' => $category->id,
            'status' => 'published',
            'publication_date' => today()->subDay(),
        ]);
        $this->createReport([
            'title_en' => 'Private draft report',
            'category_id' => $category->id,
            'status' => 'draft',
            'publication_date' => today()->subDay(),
        ]);
        $this->createReport([
            'title_en' => 'Not yet published report',
            'category_id' => $category->id,
            'status' => 'published',
            'publication_date' => today()->addDay(),
        ]);

        $this->get(route('reports.index', [
            'search' => 'audit',
            'category' => $category->slug,
            'fiscal_year' => $year->id,
            'published_from' => today()->subDays(3)->toDateString(),
            'published_to' => today()->toDateString(),
            'sort' => 'oldest',
        ]))
            ->assertOk()
            ->assertSee('Institutional Reports &amp; Publications', false)
            ->assertSee($matchingReport->title_en)
            ->assertSee('name="search"', false)
            ->assertSee('name="category"', false)
            ->assertSee('name="fiscal_year"', false)
            ->assertSee('name="published_from"', false)
            ->assertSee('name="published_to"', false)
            ->assertSee('name="sort"', false)
            ->assertSee(route('annual-reports.index'), false)
            ->assertDontSee('A different annual report')
            ->assertDontSee('Private draft report')
            ->assertDontSee('Not yet published report');
    }

    public function test_public_report_page_previews_documents_and_counts_downloads(): void
    {
        Storage::fake('local');
        $report = $this->createReport([
            'status' => 'published',
            'publication_date' => today()->subDay(),
        ]);
        Storage::disk('local')->put($report->document_path, '%PDF-1.4 test document');

        $this->get(route('reports.show', $report))
            ->assertOk()
            ->assertSee($report->title_en)
            ->assertSee(route('reports.document', $report), false)
            ->assertSee('data-report-fullscreen="report-document-viewer"', false)
            ->assertSee('Print report');

        $this->get(route('reports.document', $report))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('x-content-type-options', 'nosniff');

        $this->get(route('reports.download', $report))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->assertSame(1, $report->fresh()->downloads);
        $this->assertSame(1, $report->fresh()->views);
        $this->assertSame(1, $report->fresh()->document_views);
    }

    public function test_draft_reports_and_their_files_are_not_publicly_accessible(): void
    {
        Storage::fake('local');
        $report = $this->createReport([
            'status' => 'draft',
            'publication_date' => today()->subDay(),
            'featured_image_path' => 'report-images/private.jpg',
        ]);
        Storage::disk('local')->put($report->document_path, '%PDF-1.4 private');
        Storage::disk('local')->put($report->featured_image_path, 'private image');

        $this->get(route('reports.show', $report))->assertNotFound();
        $this->get(route('reports.document', $report))->assertNotFound();
        $this->get(route('reports.download', $report))->assertNotFound();
        $this->get(route('reports.image', $report))->assertNotFound();
        $this->get(route('reports.attachments.download', [$report, $report->attachments()->create([
            'original_name' => 'private.pdf',
            'file_path' => 'report-attachments/private.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 100,
            'is_public' => true,
        ])]))->assertNotFound();
        $this->assertSame(0, $report->fresh()->downloads);
    }

    public function test_only_report_managers_can_access_the_admin_module(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)
            ->get(route('admin.reports.index'))
            ->assertForbidden();

        $this->actingAs($member)
            ->get(route('admin.report-taxonomy.index'))
            ->assertForbidden();
    }

    public function test_staff_with_report_management_permission_can_open_the_module(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $staff = User::factory()->create();
        $role = Role::query()->where('slug', 'accountant')->firstOrFail();
        $role->permissions()->attach(
            Permission::query()->where('slug', 'admin.access')->firstOrFail()->id,
        );
        $staff->roles()->attach($role);

        $this->assertFalse($staff->can('create', Report::class));

        $role->permissions()->attach(
            Permission::query()->where('slug', 'reports.manage')->firstOrFail()->id,
        );
        $this->assertTrue($staff->fresh()->can('create', Report::class));

        $this->actingAs($staff)
            ->get(route('admin.reports.index'))
            ->assertOk()
            ->assertSee('Reports Dashboard');

        $this->get(route('admin.report-taxonomy.index'))
            ->assertOk()
            ->assertSee('Fiscal Years');
    }

    public function test_administrator_can_upload_a_report_to_private_storage(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $category = ReportCategory::query()->where('slug', 'annual-reports')->firstOrFail();
        $year = FiscalYear::query()->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.reports.create'))
            ->assertOk()
            ->assertSee('Upload PDF File');

        $this->actingAs($admin)
            ->post(route('admin.reports.store'), [
                'title_en' => 'Annual report 2082',
                'title_np' => 'वार्षिक प्रतिवेदन २०८२',
                'slug' => 'annual-report-2082',
                'category_id' => $category->id,
                'fiscal_year_id' => $year->id,
                'publication_date' => today()->toDateString(),
                'short_description' => 'A summary of the cooperative year.',
                'description' => 'Full report details.',
                'document' => UploadedFile::fake()->create('annual-report.pdf', 200, 'application/pdf'),
                'attachments' => [
                    UploadedFile::fake()->create('meeting-agenda.pdf', 100, 'application/pdf'),
                ],
                'attachments_public' => '1',
                'document_visibility' => 'private',
                'language' => 'both',
                'status' => 'draft',
                'display_order' => 1,
                'featured' => '1',
            ])
            ->assertRedirect(route('admin.reports.edit', 'annual-report-2082'));

        $report = Report::query()->where('slug', 'annual-report-2082')->firstOrFail();
        $this->assertSame('draft', $report->status);
        $this->assertTrue($report->featured);
        $this->assertStringStartsWith('reports/', $report->document_path);
        Storage::disk('local')->assertExists($report->document_path);
        $attachment = $report->attachments()->firstOrFail();
        $this->assertTrue($attachment->is_public);
        Storage::disk('local')->assertExists($attachment->file_path);
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'title_np' => 'वार्षिक प्रतिवेदन २०८२',
            'fiscal_year_id' => $year->id,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.reports.update', $report), [
                'title_en' => $report->title_en,
                'title_np' => $report->title_np,
                'slug' => $report->slug,
                'category_id' => $category->id,
                'fiscal_year_id' => $year->id,
                'publication_date' => $report->publication_date->toDateString(),
                'short_description' => $report->short_description,
                'description' => $report->description,
                'language' => $report->language,
                'status' => 'published',
                'display_order' => $report->display_order,
                'featured' => '1',
                'document_visibility' => 'public',
            ])
            ->assertRedirect(route('admin.reports.edit', $report));

        $this->get(route('reports.show', $report->fresh()))
            ->assertOk()
            ->assertSee($report->title_en);

        $this->get(route('reports.attachments.download', [$report->fresh(), $attachment]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_administrator_can_manage_taxonomy_without_deleting_assigned_values(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $this->actingAs($admin)
            ->from(route('admin.report-taxonomy.index'))
            ->post(route('admin.report-categories.store'), [
                'name_en' => 'Community Reports',
                'name_np' => 'सामुदायिक प्रतिवेदन',
                'slug' => 'community-reports',
                'description' => 'Reports about community activities.',
                'display_order' => 7,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.report-taxonomy.index'))
            ->assertSessionHas('status');

        $category = ReportCategory::query()->where('slug', 'community-reports')->firstOrFail();
        $year = FiscalYear::query()->firstOrFail();
        $report = $this->createReport([
            'category_id' => $category->id,
            'fiscal_year_id' => $year->id,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.report-taxonomy.index'))
            ->delete(route('admin.report-categories.destroy', $category))
            ->assertRedirect(route('admin.report-taxonomy.index'))
            ->assertSessionHasErrors('category');

        $this->assertDatabaseHas('reports', ['id' => $report->id]);
        $this->assertDatabaseHas('report_categories', ['id' => $category->id]);
    }

    public function test_only_verified_statistics_appear_on_public_reports_and_homepage(): void
    {
        $year = FiscalYear::query()->firstOrFail();
        $visible = ReportStatistic::query()->create([
            'fiscal_year_id' => $year->id,
            'label_en' => 'Verified member total',
            'label_np' => 'प्रमाणित सदस्य संख्या',
            'value' => 456,
            'unit_en' => 'members',
            'unit_np' => 'सदस्य',
            'display_order' => 1,
            'is_published' => true,
        ]);
        ReportStatistic::query()->create([
            'fiscal_year_id' => $year->id,
            'label_en' => 'Unverified draft total',
            'value' => 999,
            'display_order' => 2,
            'is_published' => false,
        ]);

        $this->get(route('reports.index'))
            ->assertOk()
            ->assertSee($visible->label_en)
            ->assertSee('456.00')
            ->assertDontSee('Unverified draft total')
            ->assertDontSee('999.00');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee($visible->label_en)
            ->assertDontSee('Unverified draft total');
    }

    public function test_report_detail_hides_private_pdf_and_escapes_all_public_content(): void
    {
        $report = $this->createReport([
            'status' => 'published',
            'document_visibility' => 'private',
            'title_en' => 'Public <script>alert("title")</script>',
            'short_description' => 'Summary <script>alert("summary")</script>',
            'description' => 'Detail <script>alert("detail")</script>',
            'executive_summary_en' => 'Executive <script>alert("executive")</script>',
            'reference_number' => 'REF-001',
            'published_by' => 'Public committee',
            'publication_date' => today()->subDay(),
        ]);

        $this->get(route('reports.show', $report))
            ->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertSee('Executive', false)
            ->assertSee('Public committee')
            ->assertSee('REF-001')
            ->assertDontSee(route('reports.document', $report), false)
            ->assertDontSee('alert("executive")', false);

        $this->get(route('reports.document', $report))->assertNotFound();
        $this->get(route('reports.download', $report))->assertNotFound();
    }

    public function test_reports_route_rejects_an_invalid_publication_date_range(): void
    {
        $this->get(route('reports.index', [
            'published_from' => '2026-11-20',
            'published_to' => '2026-11-01',
        ]))->assertSessionHasErrors('published_to');
    }

    public function test_upload_rejects_non_pdf_files(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $category = ReportCategory::query()->firstOrFail();
        $year = FiscalYear::query()->firstOrFail();

        $this->actingAs($admin)
            ->from(route('admin.reports.create'))
            ->post(route('admin.reports.store'), [
                'title_en' => 'Invalid document',
                'title_np' => 'अमान्य कागजात',
                'slug' => 'invalid-document',
                'category_id' => $category->id,
                'fiscal_year_id' => $year->id,
                'publication_date' => today()->toDateString(),
                'short_description' => 'Invalid file test.',
                'document' => UploadedFile::fake()->create('document.txt', 10, 'text/plain'),
                'language' => 'both',
                'status' => 'draft',
                'display_order' => 0,
            ])
            ->assertRedirect(route('admin.reports.create'))
            ->assertSessionHasErrors('document');

        $this->assertDatabaseMissing('reports', ['slug' => 'invalid-document']);
    }

    private function createReport(array $attributes = []): Report
    {
        $category = ReportCategory::query()->firstOrFail();
        $year = FiscalYear::query()->firstOrFail();

        return Report::query()->create(array_merge([
            'title_en' => 'Example institutional report',
            'title_np' => 'संस्थागत प्रतिवेदन',
            'slug' => 'example-report-'.uniqid(),
            'category_id' => $category->id,
            'fiscal_year_id' => $year->id,
            'publication_date' => today(),
            'short_description' => 'An official publication from the cooperative.',
            'document_path' => 'reports/example.pdf',
            'document_size' => 1024,
            'document_visibility' => 'public',
            'language' => 'both',
            'status' => 'published',
            'featured' => false,
            'display_order' => 0,
        ], $attributes));
    }
}
