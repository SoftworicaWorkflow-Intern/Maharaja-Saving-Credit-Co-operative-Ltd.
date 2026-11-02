<?php

namespace App\Http\Controllers;

use App\Models\AnnualReport;
use App\Models\AnnualReportChapter;
use App\Models\AnnualReportFinancialStatistic;
use App\Models\FiscalYear;
use App\Models\Report;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnnualReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'fiscal_year' => ['nullable', 'integer', 'exists:fiscal_years,id'],
        ]);

        $query = $this->publishedReports();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($query) use ($search): void {
                $query->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_np', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('short_description_np', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['fiscal_year'])) {
            $query->where('fiscal_year_id', $filters['fiscal_year']);
        }

        $latestReport = (clone $query)
            ->orderByDesc('publication_date')
            ->orderByDesc('id')
            ->first();

        $featuredReports = (clone $query)
            ->where('featured', true)
            ->orderBy('display_order')
            ->orderByDesc('publication_date')
            ->limit(3)
            ->get();

        return view('frontend.annual-reports', [
            'latestReport' => $latestReport,
            'featuredReports' => $featuredReports,
            'reports' => $query
                ->orderByDesc(FiscalYear::query()
                    ->select('display_order')
                    ->whereColumn('fiscal_years.id', 'reports.fiscal_year_id'))
                ->orderByDesc('publication_date')
                ->orderByDesc('id')
                ->paginate(9)
                ->withQueryString(),
            'fiscalYears' => FiscalYear::query()
                ->where('is_active', true)
                ->whereHas('reports', fn ($query) => $query
                    ->published()
                    ->whereHas('category', fn ($query) => $query
                        ->where('slug', 'annual-reports')
                        ->where('is_active', true))
                    ->whereHas('annualReport'))
                ->orderByDesc('display_order')
                ->orderByDesc('id')
                ->get(),
            'filters' => $filters,
        ]);
    }

    public function show(Report $report): View
    {
        abort_unless(AnnualReport::query()->published()->whereBelongsTo($report)->exists(), 404);

        $report->increment('views');
        $report->load([
            'category',
            'fiscalYear',
            'annualReport.chapters' => fn ($query) => $query
                ->where('is_published', true)
                ->where(fn ($query) => $query
                    ->whereNotNull('content_en')
                    ->orWhereNotNull('content_np')),
            'annualReport.financialStatistics' => fn ($query) => $query->where('is_published', true),
        ]);

        $relatedReports = $this->publishedReports()
            ->where('fiscal_year_id', '!=', $report->fiscal_year_id)
            ->where('id', '!=', $report->id)
            ->orderByDesc(FiscalYear::query()
                ->select('display_order')
                ->whereColumn('fiscal_years.id', 'reports.fiscal_year_id'))
            ->orderByDesc('publication_date')
            ->limit(3)
            ->get();

        $financialCharts = AnnualReportFinancialStatistic::query()
            ->published()
            ->with(['annualReport.report.fiscalYear'])
            ->get()
            ->groupBy('metric_key')
            ->map(fn ($statistics) => $statistics->sortBy(
                fn ($statistic) => $statistic->annualReport->report->fiscalYear->display_order,
            )->values());

        return view('frontend.annual-report', compact('report', 'relatedReports', 'financialCharts'));
    }

    public function chapterImage(Report $report, AnnualReportChapter $chapter): StreamedResponse
    {
        abort_unless(
            AnnualReport::query()->published()->whereBelongsTo($report)->exists()
                && $chapter->is_published
                && $chapter->annual_report_id === $report->annualReport?->id
                && is_string($chapter->image_path)
                && Storage::disk('local')->exists($chapter->image_path),
            404,
        );

        return Storage::disk('local')->response(
            $chapter->image_path,
            null,
            ['X-Content-Type-Options' => 'nosniff'],
            'inline',
        );
    }

    private function publishedReports(): Builder
    {
        return Report::query()
            ->published()
            ->whereHas('category', fn ($query) => $query
                ->where('slug', 'annual-reports')
                ->where('is_active', true))
            ->whereHas('fiscalYear', fn ($query) => $query->where('is_active', true))
            ->whereHas('annualReport')
            ->with(['category', 'fiscalYear']);
    }
}
