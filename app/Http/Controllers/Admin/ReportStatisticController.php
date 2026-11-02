<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FiscalYear;
use App\Models\ReportStatistic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportStatisticController extends Controller
{
    public function index(): View
    {
        return view('admin.reports.statistics', [
            'statistics' => ReportStatistic::query()->with('fiscalYear')->orderBy('display_order')->orderByDesc('id')->paginate(20),
            'fiscalYears' => FiscalYear::query()->orderByDesc('display_order')->orderByDesc('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        ReportStatistic::query()->create($this->validatedAttributes($request));

        return back()->with('status', 'Verified statistic saved successfully.');
    }

    public function update(Request $request, ReportStatistic $reportStatistic): RedirectResponse
    {
        $reportStatistic->update($this->validatedAttributes($request, $reportStatistic));

        return back()->with('status', 'Statistic updated successfully.');
    }

    public function destroy(ReportStatistic $reportStatistic): RedirectResponse
    {
        $reportStatistic->delete();

        return back()->with('status', 'Statistic deleted successfully.');
    }

    private function validatedAttributes(Request $request, ?ReportStatistic $statistic = null): array
    {
        $attributes = $request->validate([
            'fiscal_year_id' => ['required', 'integer', Rule::exists(FiscalYear::class, 'id')],
            'label_en' => ['required', 'string', 'max:150'],
            'label_np' => ['nullable', 'string', 'max:150'],
            'value' => ['required', 'numeric', 'min:0', 'max:999999999999999999.99'],
            'unit_en' => ['nullable', 'string', 'max:40'],
            'unit_np' => ['nullable', 'string', 'max:40'],
            'display_order' => ['required', 'integer', 'min:0', 'max:65535'],
            'is_published' => ['sometimes', 'boolean'],
        ]);
        $attributes['is_published'] = $request->boolean('is_published');

        return $attributes;
    }
}
