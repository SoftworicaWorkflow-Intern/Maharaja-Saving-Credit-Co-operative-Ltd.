<?php

namespace Tests\Feature;

use App\Models\AnnualReportChapter;
use App\Models\FiscalYear;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AnnualReportManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_publish_annual_chapters_and_verified_financial_data(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $category = ReportCategory::query()->where('slug', 'annual-reports')->firstOrFail();
        $year = FiscalYear::query()->firstOrFail();
        $payload = $this->annualReportPayload($category->id, $year->id);

        $this->actingAs($admin)
            ->get(route('admin.annual-reports.create'))
            ->assertRedirect(route('admin.reports.create', ['category_id' => $category->id]));

        $this->followingRedirects()
            ->get(route('admin.annual-reports.create'))
            ->assertOk()
            ->assertSee('Create Annual Report')
            ->assertSee('Annual report chapters');

        $this->actingAs($admin)
            ->post(route('admin.reports.store'), $payload)
            ->assertRedirect(route('admin.reports.edit', 'annual-progress-2080'));

        $report = Report::query()->where('slug', 'annual-progress-2080')->firstOrFail();
        $this->actingAs($admin)
            ->get(route('admin.annual-reports.index'))
            ->assertOk()
            ->assertSee('Annual Reports Management')
            ->assertSee('Annual progress 2080');

        $this->assertDatabaseHas('annual_reports', ['report_id' => $report->id]);
        $this->assertDatabaseHas('annual_report_chapters', [
            'chapter_key' => 'chairpersons-message',
            'content_np' => 'सदस्यहरूलाई धन्यवाद।',
            'is_published' => true,
        ]);
        $this->assertDatabaseHas('annual_report_financial_statistics', [
            'metric_key' => 'total_savings',
            'value' => 125000.00,
            'is_published' => true,
        ]);
        $this->assertDatabaseHas('annual_report_financial_statistics', [
            'metric_key' => 'total_loans',
            'value' => 90000.00,
            'is_published' => false,
        ]);
        $this->get(route('admin.reports.edit', $report))
            ->assertOk()
            ->assertSee('An approved message from the chairperson.')
            ->assertSee('125000.00');

        $chairpersonChapter = AnnualReportChapter::query()
            ->where('annual_report_id', $report->annualReport->id)
            ->where('chapter_key', 'chairpersons-message')
            ->firstOrFail();
        $this->get(route('annual-reports.chapters.image', [$report, $chairpersonChapter]))
            ->assertOk()
            ->assertHeader('content-type', 'image/png')
            ->assertHeader('x-content-type-options', 'nosniff');
        $this->get(route('annual-reports.index', ['fiscal_year' => $year->id]))
            ->assertOk()
            ->assertSee($report->title_en)
            ->assertSee($year->localizedLabel());

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Latest Annual Report')
            ->assertSee($report->title_en);

        $comparisonYear = FiscalYear::query()->where('id', '!=', $year->id)->firstOrFail();
        $comparisonReport = Report::query()->create([
            'title_en' => 'Annual progress comparison',
            'title_np' => 'वार्षिक प्रगति तुलना',
            'slug' => 'annual-progress-comparison',
            'category_id' => $category->id,
            'fiscal_year_id' => $comparisonYear->id,
            'publication_date' => today()->subYear(),
            'short_description' => 'A prior-year annual report.',
            'document_path' => 'reports/comparison.pdf',
            'document_size' => 100,
            'document_visibility' => 'private',
            'language' => 'both',
            'status' => 'published',
            'featured' => false,
            'display_order' => 0,
        ]);
        $comparisonAnnualReport = $comparisonReport->annualReport()->create([]);
        $comparisonAnnualReport->financialStatistics()->create([
            'metric_key' => 'total_savings',
            'value' => 175000,
            'is_published' => true,
        ]);
        $this->get(route('annual-reports.chapters.image', [$comparisonReport, $chairpersonChapter]))
            ->assertNotFound();

        $this->get(route('annual-reports.show', $report))
            ->assertOk()
            ->assertSee('A year of service')
            ->assertSee('125,000.00')
            ->assertSee('175,000.00')
            ->assertDontSee('90,000.00')
            ->assertDontSee('An unpublished draft chapter');

        $this->withSession(['locale' => 'ne'])
            ->get(route('annual-reports.show', $report))
            ->assertOk()
            ->assertSee('सदस्यहरूलाई धन्यवाद।');

        $payload['annual_chapters'] = [
            [
                'chapter_key' => 'chairpersons-message',
                'title_en' => "Chairperson's Annual Message",
                'title_np' => 'अध्यक्षको वार्षिक सन्देश',
                'content_en' => 'Updated and approved message.',
                'content_np' => 'अद्यावधिक सन्देश।',
                'display_order' => 3,
                'is_published' => '1',
            ],
        ];
        $payload['annual_financials'] = [
            [
                'metric_key' => 'total_savings',
                'value' => '150000.00',
                'comparison_value' => '125000.00',
                'is_published' => '1',
            ],
        ];
        unset($payload['document']);

        $this->put(route('admin.reports.update', $report), $payload)
            ->assertRedirect(route('admin.reports.edit', $report));

        $this->assertSame(1, AnnualReportChapter::query()
            ->where('annual_report_id', $report->annualReport->id)
            ->count());
        $this->assertDatabaseHas('annual_report_chapters', [
            'chapter_key' => 'chairpersons-message',
            'content_en' => 'Updated and approved message.',
            'display_order' => 3,
        ]);
        $this->assertDatabaseMissing('annual_report_financial_statistics', ['metric_key' => 'total_loans']);

        $this->withSession(['locale' => 'en'])
            ->get(route('reports.show', $report->fresh()))
            ->assertOk()
            ->assertSee('Updated and approved message.');

        $chapterImagePath = $chairpersonChapter->fresh()->image_path;
        $this->assertNotNull($chapterImagePath);
        $this->delete(route('admin.reports.destroy', $report))
            ->assertRedirect(route('admin.reports.index'));

        Storage::disk('local')->assertMissing($chapterImagePath);
    }

    public function test_annual_reports_dashboard_requires_reports_permission(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)
            ->get(route('admin.annual-reports.index'))
            ->assertForbidden();
    }

    private function annualReportPayload(int $categoryId, int $fiscalYearId): array
    {
        return [
            'title_en' => 'Annual progress 2080',
            'title_np' => 'वार्षिक प्रगति २०८०',
            'slug' => 'annual-progress-2080',
            'category_id' => $categoryId,
            'fiscal_year_id' => $fiscalYearId,
            'publication_date' => today()->toDateString(),
            'reference_number' => 'AR-2080',
            'short_description_en' => 'A verified annual institutional report.',
            'short_description_np' => 'प्रमाणित वार्षिक संस्थागत प्रतिवेदन।',
            'executive_summary_en' => 'A year of service',
            'executive_summary_np' => 'सेवाको एक वर्ष',
            'document' => UploadedFile::fake()->create('annual-report.pdf', 100, 'application/pdf'),
            'document_visibility' => 'public',
            'language' => 'both',
            'status' => 'published',
            'display_order' => 1,
            'featured' => '1',
            'annual_chapters' => [
                [
                    'chapter_key' => 'chairpersons-message',
                    'title_en' => "Chairperson's Annual Message",
                    'title_np' => 'अध्यक्षको वार्षिक सन्देश',
                    'content_en' => 'An approved message from the chairperson.',
                    'content_np' => 'सदस्यहरूलाई धन्यवाद।',
                    'image' => UploadedFile::fake()->createWithContent(
                        'chairperson.png',
                        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/r9sAAAAASUVORK5CYII='),
                    ),
                    'display_order' => 1,
                    'is_published' => '1',
                ],
                [
                    'chapter_key' => 'annual-highlights',
                    'title_en' => 'Annual Highlights',
                    'title_np' => 'वार्षिक मुख्य उपलब्धिहरू',
                    'content_en' => 'An unpublished draft chapter.',
                    'content_np' => '',
                    'display_order' => 2,
                ],
            ],
            'annual_financials' => [
                [
                    'metric_key' => 'total_savings',
                    'value' => '125000.00',
                    'comparison_value' => '100000.00',
                    'is_published' => '1',
                ],
                [
                    'metric_key' => 'total_loans',
                    'value' => '90000.00',
                ],
            ],
        ];
    }
}
