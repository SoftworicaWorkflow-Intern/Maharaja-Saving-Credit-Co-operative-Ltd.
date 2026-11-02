<?php

namespace Tests\Feature;

use App\Models\FiscalYear;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\ReportStatistic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ReportCategoryPagesTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('categoryPages')]
    public function test_category_pages_have_dedicated_routes_and_category_scoped_archives(
        string $slug,
        string $routeName,
        string $heading,
    ): void {
        $category = ReportCategory::query()->where('slug', $slug)->firstOrFail();
        $year = FiscalYear::query()->firstOrFail();
        $report = $this->createReport($category, $year, "Published {$slug} publication");
        if ($slug === 'annual-reports') {
            $report->annualReport()->create([]);
        }
        $otherCategory = ReportCategory::query()->where('slug', '!=', $slug)->firstOrFail();
        $this->createReport($otherCategory, $year, "Other category {$slug} publication");

        $this->get(route($routeName, [
            'search' => $slug,
            'fiscal_year' => $year->id,
            'category' => $otherCategory->slug,
        ]))
            ->assertOk()
            ->assertSee($heading)
            ->assertSee($report->title_en)
            ->assertDontSee("Other category {$slug} publication");
    }

    public function test_all_reports_page_links_to_dedicated_category_archives(): void
    {
        $response = $this->get(route('reports.index'))->assertOk();

        foreach (array_column(self::categoryPages(), 1) as $routeName) {
            $response->assertSee(route($routeName), false);
        }
    }

    public function test_administrator_created_categories_get_clean_public_archive_urls(): void
    {
        $category = ReportCategory::query()->create([
            'name_en' => 'Member resources',
            'name_np' => 'सदस्य स्रोतहरू',
            'slug' => 'member-resources',
            'description' => 'Approved resources prepared for members.',
            'display_order' => 20,
            'is_active' => true,
        ]);
        $categoryUrl = route('reports.category.custom', $category->slug);

        $this->get(route('reports.index'))
            ->assertOk()
            ->assertSee($categoryUrl, false)
            ->assertDontSee(route('reports.index', ['category' => $category->slug]), false);

        $this->get($categoryUrl)
            ->assertOk()
            ->assertSee($category->name_en)
            ->assertSee($category->description)
            ->assertSee('No publications available');
    }

    public function test_financial_archive_shows_only_published_verified_statistics(): void
    {
        $year = FiscalYear::query()->firstOrFail();
        $publishedStatistic = ReportStatistic::query()->create([
            'fiscal_year_id' => $year->id,
            'label_en' => 'Verified savings balance',
            'value' => 230000,
            'unit_en' => 'NPR',
            'display_order' => 1,
            'is_published' => true,
        ]);
        ReportStatistic::query()->create([
            'fiscal_year_id' => $year->id,
            'label_en' => 'Unapproved draft balance',
            'value' => 999999,
            'unit_en' => 'NPR',
            'display_order' => 2,
            'is_published' => false,
        ]);

        $this->get(route('reports.financial'))
            ->assertOk()
            ->assertSee($publishedStatistic->label_en)
            ->assertSee('230,000.00')
            ->assertDontSee('Unapproved draft balance')
            ->assertDontSee('999,999.00');
    }

    public function test_financial_growth_charts_use_only_published_annual_report_figures(): void
    {
        $category = ReportCategory::query()->where('slug', 'annual-reports')->firstOrFail();
        $years = FiscalYear::query()->orderBy('display_order')->take(2)->get();
        $currentReport = $this->createReport($category, $years[0], 'Financial year one annual report');
        $currentAnnualReport = $currentReport->annualReport()->create([]);
        $currentAnnualReport->financialStatistics()->create([
            'metric_key' => 'total_savings',
            'value' => 100,
            'is_published' => true,
        ]);
        $currentAnnualReport->financialStatistics()->create([
            'metric_key' => 'total_loans',
            'value' => 900,
            'is_published' => false,
        ]);
        $priorReport = $this->createReport($category, $years[1], 'Financial year two annual report');
        $priorAnnualReport = $priorReport->annualReport()->create([]);
        $priorAnnualReport->financialStatistics()->create([
            'metric_key' => 'total_savings',
            'value' => 150,
            'is_published' => true,
        ]);

        $this->get(route('reports.financial'))
            ->assertOk()
            ->assertSee('Total savings')
            ->assertSee('100.00')
            ->assertSee('150.00')
            ->assertDontSee('900.00');

        $this->get(route('reports.financial', ['fiscal_year' => $years[0]->id]))
            ->assertOk()
            ->assertSee('100.00')
            ->assertDontSee('150.00');
    }

    public function test_all_reports_page_shows_charts_for_published_annual_figures_only(): void
    {
        $category = ReportCategory::query()->where('slug', 'annual-reports')->firstOrFail();
        $years = FiscalYear::query()->orderBy('display_order')->take(2)->get();
        $report = $this->createReport($category, $years[0], 'Published annual report with approved figures');
        $annualReport = $report->annualReport()->create([]);
        $annualReport->financialStatistics()->create([
            'metric_key' => 'total_savings',
            'value' => 100,
            'is_published' => true,
        ]);
        $annualReport->financialStatistics()->create([
            'metric_key' => 'total_loans',
            'value' => 900,
            'is_published' => false,
        ]);
        $priorReport = $this->createReport($category, $years[1], 'Prior annual report with approved figures');
        $priorReport->annualReport()->create([])
            ->financialStatistics()
            ->create([
                'metric_key' => 'total_savings',
                'value' => 150,
                'is_published' => true,
            ]);

        $this->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Yearly financial performance')
            ->assertSee('Total savings')
            ->assertSee('100.00')
            ->assertSee('150.00')
            ->assertDontSee('900.00');

        $this->get(route('reports.index', ['fiscal_year' => $years[0]->id]))
            ->assertOk()
            ->assertSee('100.00')
            ->assertDontSee('150.00');
    }

    public function test_all_reports_page_explains_when_there_are_no_published_financial_figures(): void
    {
        $this->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Financial charts will appear when verified figures are published in annual reports.')
            ->assertDontSee('Total savings');
    }

    public function test_category_page_content_uses_nepali_translations_when_selected(): void
    {
        $this->withSession(['locale' => 'ne'])
            ->get(route('reports.audit'))
            ->assertOk()
            ->assertSee('लेखापरीक्षण प्रतिवेदन तथा वित्तीय जवाफदेहिता')
            ->assertSee('जवाफदेहिता तथा निगरानी')
            ->assertSee('आर्थिक वर्षअनुसार व्यवस्थित स्वीकृत लेखापरीक्षण प्रकाशन');
    }

    public function test_admin_can_save_category_specific_metadata_and_unrecognized_fields_are_rejected(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $category = ReportCategory::query()->where('slug', 'financial-reports')->firstOrFail();
        $year = FiscalYear::query()->firstOrFail();
        $payload = [
            'title_en' => 'Verified financial statement',
            'title_np' => 'प्रमाणित वित्तीय विवरण',
            'slug' => 'verified-financial-statement',
            'category_id' => $category->id,
            'fiscal_year_id' => $year->id,
            'publication_date' => today()->toDateString(),
            'short_description_en' => 'Approved statement for the financial archive.',
            'document' => UploadedFile::fake()->create('statement.pdf', 100, 'application/pdf'),
            'document_visibility' => 'private',
            'language' => 'both',
            'status' => 'draft',
            'display_order' => 0,
            'category_metadata' => [
                'statement_type' => 'balance-sheet',
                'reporting_period' => '२०८२/०८३',
            ],
        ];

        $this->actingAs($admin)
            ->get(route('admin.reports.create', ['category_id' => $category->id]))
            ->assertOk()
            ->assertSee('Financial statement details')
            ->assertSee('data-category-slug="financial-reports"', false);

        $this->actingAs($admin)
            ->post(route('admin.reports.store'), $payload)
            ->assertRedirect(route('admin.reports.edit', 'verified-financial-statement'));

        $this->assertDatabaseHas('reports', ['slug' => 'verified-financial-statement']);
        $this->assertSame(
            [
                'statement_type' => 'balance-sheet',
                'reporting_period' => '२०८२/०८३',
            ],
            Report::query()->where('slug', 'verified-financial-statement')->firstOrFail()->category_metadata,
        );

        $payload['slug'] = 'financial-statement-with-unexpected-metadata';
        $payload['category_metadata']['auditor_name'] = 'Not valid for this category';
        $this->from(route('admin.reports.create'))
            ->post(route('admin.reports.store'), $payload)
            ->assertRedirect(route('admin.reports.create'))
            ->assertSessionHasErrors('category_metadata');

        $this->assertDatabaseMissing('reports', ['slug' => $payload['slug']]);
    }

    public function test_public_report_details_render_category_metadata_with_escaped_values(): void
    {
        $category = ReportCategory::query()->where('slug', 'audit-reports')->firstOrFail();
        $year = FiscalYear::query()->firstOrFail();
        $report = $this->createReport($category, $year, 'Published audit publication');
        $report->update([
            'category_metadata' => [
                'audit_type' => 'external',
                'auditor_name' => '<script>alert("name")</script>',
                'audit_period' => '२०८२/०८३',
            ],
        ]);

        $this->get(route('reports.show', $report))
            ->assertOk()
            ->assertSee('Audit type')
            ->assertSee('External')
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert("name")</script>', false);
    }

    public static function categoryPages(): array
    {
        return [
            'annual reports' => ['annual-reports', 'annual-reports.index', 'Annual Reports & Institutional Progress'],
            'financial reports' => ['financial-reports', 'reports.financial', 'Financial Reports & Performance'],
            'audit reports' => ['audit-reports', 'reports.audit', 'Audit Reports & Financial Accountability'],
            'agm reports' => ['agm-reports', 'reports.agm', 'Annual General Meeting Reports'],
            'progress reports' => ['progress-reports', 'reports.progress', 'Institutional Progress & Development Reports'],
            'policies and guidelines' => ['policies-guidelines', 'reports.policies', 'Institutional Policies & Guidelines'],
            'meeting decisions' => ['meeting-decisions', 'reports.decisions', 'Institutional Meeting Decisions & Resolutions'],
            'other publications' => ['other-publications', 'reports.other', 'Official Publications & Institutional Resources'],
        ];
    }

    private function createReport(ReportCategory $category, FiscalYear $year, string $title): Report
    {
        $slug = str($title)->slug()->toString();

        return Report::query()->create([
            'title_en' => $title,
            'title_np' => 'संस्थागत प्रकाशन',
            'slug' => $slug,
            'category_id' => $category->id,
            'fiscal_year_id' => $year->id,
            'publication_date' => today(),
            'short_description' => 'An officially approved test publication.',
            'category_metadata' => null,
            'document_path' => "reports/{$slug}.pdf",
            'document_size' => 1024,
            'document_visibility' => 'private',
            'language' => 'both',
            'status' => 'published',
            'featured' => false,
            'display_order' => 0,
        ]);
    }
}
