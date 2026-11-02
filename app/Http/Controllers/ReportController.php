<?php

namespace App\Http\Controllers;

use App\Models\AnnualReportFinancialStatistic;
use App\Models\FiscalYear;
use App\Models\Report;
use App\Models\ReportAttachment;
use App\Models\ReportCategory;
use App\Models\ReportStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        return $this->listing($request);
    }

    public function category(Request $request, string $slug): View
    {
        $category = ReportCategory::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        $page = config("report_pages.pages.$slug") ?? [
            'title' => $category->localizedName(),
            'seo_description' => $category->description ?: __('Browse approved publications in this collection.'),
            'eyebrow' => __('Official Publications'),
            'description' => $category->description ?: __('Browse approved publications in this collection.'),
            'intro_heading' => __('Browse :category publications', ['category' => $category->localizedName()]),
            'intro' => $category->description ?: __('Only approved publications are shown in this collection.'),
            'sections' => [
                [
                    'title' => __('Official publications'),
                    'description' => $category->description ?: __('Browse approved publications in this collection.'),
                ],
            ],
            'archive_heading' => __(':category archive', ['category' => $category->localizedName()]),
            'empty_title' => __('No publications available'),
            'empty_description' => __('No approved publications match these filters.'),
            'show_statistics' => false,
        ];

        return $this->listing($request, $category, $page);
    }

    public function show(Report $report): View
    {
        abort_unless($this->isPublic($report), 404);

        if ($report->category->slug === 'annual-reports' && $report->annualReport()->exists()) {
            return app(AnnualReportController::class)->show($report);
        }

        $report->increment('views');
        $report->load([
            'category',
            'fiscalYear',
            'attachments' => fn ($query) => $query->where('is_public', true),
        ]);
        $relatedReports = Report::query()
            ->published()
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->whereHas('fiscalYear', fn ($query) => $query->where('is_active', true))
            ->with(['category', 'fiscalYear'])
            ->where('category_id', $report->category_id)
            ->where('id', '!=', $report->getKey())
            ->orderByDesc('publication_date')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        return view('frontend.report', compact('report', 'relatedReports'));
    }

    public function document(Report $report): StreamedResponse
    {
        abort_unless($this->isPublic($report) && $report->document_visibility === 'public', 404);
        abort_unless(Storage::disk('local')->exists($report->document_path), 404);

        $report->increment('document_views');

        return Storage::disk('local')->response(
            $report->document_path,
            $report->slug.'.pdf',
            [
                'Content-Type' => 'application/pdf',
                'X-Content-Type-Options' => 'nosniff',
            ],
            'inline',
        );
    }

    public function download(Report $report): StreamedResponse
    {
        abort_unless($this->isPublic($report) && $report->document_visibility === 'public', 404);
        abort_unless(Storage::disk('local')->exists($report->document_path), 404);

        $report->increment('downloads');

        return Storage::disk('local')->download(
            $report->document_path,
            $report->slug.'.pdf',
            [
                'Content-Type' => 'application/pdf',
                'X-Content-Type-Options' => 'nosniff',
            ],
        );
    }

    public function image(Report $report): StreamedResponse
    {
        abort_unless($this->isPublic($report), 404);
        abort_unless(
            is_string($report->featured_image_path)
                && Storage::disk('local')->exists($report->featured_image_path),
            404,
        );

        return Storage::disk('local')->response(
            $report->featured_image_path,
            null,
            ['X-Content-Type-Options' => 'nosniff'],
            'inline',
        );
    }

    public function attachment(Report $report, ReportAttachment $attachment): StreamedResponse
    {
        abort_unless(
            $this->isPublic($report)
                && $report->document_visibility === 'public'
                && $attachment->report_id === $report->id
                && $attachment->is_public,
            404,
        );
        abort_unless(Storage::disk('local')->exists($attachment->file_path), 404);

        return Storage::disk('local')->download(
            $attachment->file_path,
            $attachment->original_name,
            [
                'Content-Type' => $attachment->mime_type,
                'X-Content-Type-Options' => 'nosniff',
            ],
        );
    }

    private function listing(Request $request, ?ReportCategory $category = null, ?array $page = null): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'fiscal_year' => ['nullable', 'integer', 'exists:fiscal_years,id'],
            'published_from' => ['nullable', 'date'],
            'published_to' => ['nullable', 'date', 'after_or_equal:published_from'],
            'sort' => ['nullable', 'in:latest,oldest'],
        ]);
        if ($category !== null) {
            unset($filters['category']);
        }

        $query = Report::query()
            ->published()
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->whereHas('fiscalYear', fn ($query) => $query->where('is_active', true))
            ->with(['category', 'fiscalYear']);

        if ($category !== null) {
            $query->where('category_id', $category->id);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($query) use ($search): void {
                $query->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_np', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('short_description_np', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('description_np', 'like', "%{$search}%");
            });
        }

        if ($category === null && ! empty($filters['category'])) {
            $query->whereHas('category', function ($query) use ($filters): void {
                $query->where('slug', $filters['category'])
                    ->where('is_active', true);
            });
        }

        if (! empty($filters['fiscal_year'])) {
            $query->where('fiscal_year_id', $filters['fiscal_year']);
        }

        if (! empty($filters['published_from'])) {
            $query->whereDate('publication_date', '>=', $filters['published_from']);
        }

        if (! empty($filters['published_to'])) {
            $query->whereDate('publication_date', '<=', $filters['published_to']);
        }

        $latestReports = (clone $query)
            ->orderByDesc('publication_date')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $featuredReports = (clone $query)
            ->where('featured', true)
            ->orderBy('display_order')
            ->orderByDesc('publication_date')
            ->limit(3)
            ->get();

        $reports = $query
            ->orderBy('publication_date', ($filters['sort'] ?? 'latest') === 'oldest' ? 'asc' : 'desc')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        $categories = ReportCategory::query()
            ->where('is_active', true)
            ->withCount([
                'reports as published_reports_count' => fn ($reportQuery) => $reportQuery
                    ->published()
                    ->whereHas('fiscalYear', fn ($yearQuery) => $yearQuery->where('is_active', true)),
            ])
            ->orderBy('display_order')
            ->orderBy('name_en')
            ->get();
        $statistics = collect();
        if ($page === null || ($page['show_statistics'] ?? false)) {
            $statistics = ReportStatistic::query()
                ->published()
                ->whereHas('fiscalYear', fn ($yearQuery) => $yearQuery->where('is_active', true))
                ->when(
                    $page !== null && ! empty($filters['fiscal_year']),
                    fn ($query) => $query->where('fiscal_year_id', $filters['fiscal_year']),
                )
                ->with('fiscalYear')
                ->orderBy('display_order')
                ->orderByDesc('id')
                ->get();
        }
        $financialCharts = collect();
        if ($page === null || ($page['show_financial_charts'] ?? false)) {
            $financialChartQuery = AnnualReportFinancialStatistic::query()
                ->published()
                ->with(['annualReport.report.fiscalYear']);

            if (! empty($filters['fiscal_year'])) {
                $financialChartQuery->whereHas(
                    'annualReport.report',
                    fn ($reportQuery) => $reportQuery
                        ->where('fiscal_year_id', $filters['fiscal_year']),
                );
            }

            $financialCharts = $financialChartQuery
                ->get()
                ->groupBy('metric_key')
                ->map(fn ($metricStatistics) => $metricStatistics->sortBy(
                    fn ($statistic) => $statistic->annualReport->report->fiscalYear->display_order,
                )->values());
        }

        return view($page === null ? 'frontend.reports' : 'frontend.report-category', [
            'reports' => $reports,
            'latestReports' => $latestReports,
            'featuredReports' => $featuredReports,
            'statistics' => $statistics,
            'categories' => $categories,
            'fiscalYears' => FiscalYear::query()->where('is_active', true)->orderByDesc('display_order')->orderByDesc('id')->get(),
            'filters' => $filters,
            'category' => $category,
            'page' => $page,
            'financialCharts' => $financialCharts,
            'categoryRouteName' => $category === null
                ? null
                : (config("report_pages.routes.{$category->slug}") ?:
                    'reports.category.custom'),
            'categoryRouteParameters' => $category !== null
                && config("report_pages.routes.{$category->slug}") === null
                ? ['slug' => $category->slug]
                : [],
        ]);
    }

    private function isPublic(Report $report): bool
    {
        return $report->status === 'published'
            && $report->publication_date !== null
            && $report->publication_date->lte(today())
            && $report->category()->where('is_active', true)->exists()
            && $report->fiscalYear()->where('is_active', true)->exists();
    }
}
