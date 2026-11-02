<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FiscalYear;
use App\Models\ReportCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportTaxonomyController extends Controller
{
    public function index(): View
    {
        return view('admin.reports.taxonomy', [
            'categories' => ReportCategory::query()->withCount('reports')->orderBy('display_order')->orderBy('name_en')->get(),
            'fiscalYears' => FiscalYear::query()->withCount('reports')->orderByDesc('display_order')->orderByDesc('id')->get(),
        ]);
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        ReportCategory::query()->create($this->categoryAttributes($request));

        return back()->with('status', 'Report category created successfully.');
    }

    public function updateCategory(Request $request, ReportCategory $reportCategory): RedirectResponse
    {
        $reportCategory->update($this->categoryAttributes($request, $reportCategory));

        return back()->with('status', 'Report category updated successfully.');
    }

    public function destroyCategory(ReportCategory $reportCategory): RedirectResponse
    {
        if ($reportCategory->reports()->exists()) {
            return back()->withErrors([
                'category' => 'This category has reports assigned to it. Move those reports before deleting the category.',
            ]);
        }

        $reportCategory->delete();

        return back()->with('status', 'Report category deleted successfully.');
    }

    public function storeFiscalYear(Request $request): RedirectResponse
    {
        FiscalYear::query()->create($this->fiscalYearAttributes($request));

        return back()->with('status', 'Fiscal year created successfully.');
    }

    public function updateFiscalYear(Request $request, FiscalYear $fiscalYear): RedirectResponse
    {
        $fiscalYear->update($this->fiscalYearAttributes($request, $fiscalYear));

        return back()->with('status', 'Fiscal year updated successfully.');
    }

    public function destroyFiscalYear(FiscalYear $fiscalYear): RedirectResponse
    {
        if ($fiscalYear->reports()->exists()) {
            return back()->withErrors([
                'fiscal_year' => 'This fiscal year has reports assigned to it. Move those reports before deleting the year.',
            ]);
        }

        $fiscalYear->delete();

        return back()->with('status', 'Fiscal year deleted successfully.');
    }

    private function categoryAttributes(Request $request, ?ReportCategory $category = null): array
    {
        return $request->validate([
            'name_en' => ['required', 'string', 'max:100'],
            'name_np' => ['nullable', 'string', 'max:100'],
            'slug' => [
                'required',
                'string',
                'max:100',
                'alpha_dash:ascii',
                Rule::unique('report_categories', 'slug')->ignore($category),
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'display_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }

    private function fiscalYearAttributes(Request $request, ?FiscalYear $fiscalYear = null): array
    {
        return $request->validate([
            'label' => [
                'required',
                'string',
                'max:40',
                Rule::unique('fiscal_years', 'label')->ignore($fiscalYear),
            ],
            'label_en' => [
                'required',
                'string',
                'max:40',
                Rule::unique('fiscal_years', 'label_en')->ignore($fiscalYear),
            ],
            'display_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
