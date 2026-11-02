<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FiscalYear;
use App\Models\Report;
use App\Models\ReportCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AnnualReportController extends Controller
{
    public function index(Request $request): View
    {
        $category = ReportCategory::query()->where('slug', 'annual-reports')->firstOrFail();
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(Report::STATUSES)],
            'fiscal_year' => ['nullable', 'integer', 'exists:fiscal_years,id'],
        ]);
        $annualReports = Report::query()
            ->where('category_id', $category->id)
            ->with('fiscalYear');
        $query = clone $annualReports;

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($query) use ($search): void {
                $query->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_np', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['fiscal_year'])) {
            $query->where('fiscal_year_id', $filters['fiscal_year']);
        }

        return view('admin.annual-reports.index', [
            'reports' => (clone $query)
                ->orderByRaw("CASE WHEN status = 'draft' THEN 0 ELSE 1 END")
                ->orderByDesc(FiscalYear::query()
                    ->select('display_order')
                    ->whereColumn('fiscal_years.id', 'reports.fiscal_year_id'))
                ->orderByDesc('publication_date')
                ->paginate(20)
                ->withQueryString(),
            'categoryId' => $category->id,
            'filters' => $filters,
            'fiscalYears' => FiscalYear::query()
                ->orderByDesc('display_order')
                ->orderByDesc('id')
                ->get(),
            'stats' => [
                'total' => (clone $annualReports)->count(),
                'published' => (clone $annualReports)->where('status', 'published')->count(),
                'drafts' => (clone $annualReports)->where('status', 'draft')->count(),
                'featured' => (clone $annualReports)->where('featured', true)->count(),
                'views' => (int) (clone $annualReports)->sum('views'),
            ],
        ]);
    }

    public function create(): RedirectResponse
    {
        $categoryId = ReportCategory::query()
            ->where('slug', 'annual-reports')
            ->firstOrFail()
            ->getKey();

        return redirect()->route('admin.reports.create', ['category_id' => $categoryId]);
    }
}
