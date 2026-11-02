@extends('layouts.admin')

@section('title', __('Reports Dashboard'))
@section('page-title', __('Reports Management'))

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-[#182A22]">{{ __('Reports Dashboard') }}</h2>
        <p class="mt-1 text-sm text-[#617067]">{{ __('Manage publication, categories, fiscal years, and report activity.') }}</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.report-statistics.index') }}" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm font-semibold text-[#14532D] hover:bg-[#F5F8F5]">{{ __('Verified statistics') }}</a>
        <a href="{{ route('admin.report-taxonomy.index') }}" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm font-semibold text-[#14532D] hover:bg-[#F5F8F5]">{{ __('Categories & fiscal years') }}</a>
        <a href="{{ route('admin.reports.create') }}" class="rounded-xl bg-[#14532D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#27804A]">{{ __('Add New Report') }}</a>
    </div>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#14532D]">{{ session('status') }}</div>
@endif

<section class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="{{ __('Report statistics') }}">
    @foreach ([
        ['label' => __('Total reports'), 'value' => $stats['total']],
        ['label' => __('Published'), 'value' => $stats['published']],
        ['label' => __('Drafts'), 'value' => $stats['drafts']],
        ['label' => __('Featured reports'), 'value' => $stats['featured']],
        ['label' => __('Total categories'), 'value' => $stats['categories']],
        ['label' => __('Total report views'), 'value' => $stats['views']],
        ['label' => __('Total document views'), 'value' => $stats['document_views']],
        ['label' => __('Total downloads'), 'value' => $stats['downloads']],
    ] as $stat)
        <div class="rounded-xl border border-[#DCE9DF] bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-[#617067]">{{ $stat['label'] }}</p>
            <p class="mt-2 text-3xl font-extrabold text-[#14532D]">{{ number_format($stat['value']) }}</p>
        </div>
    @endforeach
</section>

<form method="GET" action="{{ route('admin.reports.index') }}" class="mb-5 grid gap-3 rounded-xl border border-[#DCE9DF] bg-white p-4 sm:grid-cols-2 xl:grid-cols-[minmax(12rem,1fr)_180px_200px_200px_auto]">
    <label class="sr-only" for="reports-search">{{ __('Search reports') }}</label>
    <input id="reports-search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('Search title or slug') }}" class="rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-2.5 text-sm">
    <label class="sr-only" for="reports-status">{{ __('Filter status') }}</label>
    <select id="reports-status" name="status" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm">
        <option value="">{{ __('All statuses') }}</option>
        @foreach (\App\Models\Report::STATUSES as $status)
            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ __(ucfirst($status)) }}</option>
        @endforeach
    </select>
    <label class="sr-only" for="reports-category">{{ __('Filter category') }}</label>
    <select id="reports-category" name="category" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm">
        <option value="">{{ __('All categories') }}</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected((string) ($filters['category'] ?? '') === (string) $category->id)>{{ $category->name_en }}</option>
        @endforeach
    </select>
    <label class="sr-only" for="reports-year">{{ __('Filter fiscal year') }}</label>
    <select id="reports-year" name="fiscal_year" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm">
        <option value="">{{ __('All fiscal years') }}</option>
        @foreach ($fiscalYears as $fiscalYear)
            <option value="{{ $fiscalYear->id }}" @selected((string) ($filters['fiscal_year'] ?? '') === (string) $fiscalYear->id)>{{ $fiscalYear->label }}</option>
        @endforeach
    </select>
    <button type="submit" class="rounded-xl bg-[#14532D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#27804A]">{{ __('Filter') }}</button>
</form>

<div class="overflow-hidden rounded-xl border border-[#DCE9DF] bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-[#E8EFEA] text-left text-sm">
            <thead class="bg-[#F5F8F5] text-xs uppercase tracking-wide text-[#617067]">
                <tr>
                    <th scope="col" class="px-5 py-3">{{ __('Report') }}</th>
                    <th scope="col" class="px-5 py-3">{{ __('Category') }}</th>
                    <th scope="col" class="px-5 py-3">{{ __('Fiscal year') }}</th>
                    <th scope="col" class="px-5 py-3">{{ __('Status') }}</th>
                    <th scope="col" class="px-5 py-3">{{ __('Downloads') }}</th>
                    <th scope="col" class="px-5 py-3"><span class="sr-only">{{ __('Actions') }}</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E8EFEA]">
                @forelse ($reports as $report)
                    <tr>
                        <td class="min-w-64 px-5 py-4">
                            <a href="{{ route('admin.reports.edit', $report) }}" class="font-semibold text-[#14532D] hover:underline">{{ $report->title_en }}</a>
                            <p lang="ne" class="mt-1 text-xs text-[#617067]">{{ $report->title_np }}</p>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-[#344054]">{{ $report->category->name_en }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-[#344054]">{{ $report->fiscalYear->label }}</td>
                        <td class="px-5 py-4"><span @class(['rounded-full px-3 py-1 text-xs font-semibold', 'bg-amber-100 text-amber-800' => $report->status === 'draft', 'bg-[#E7F4EB] text-[#14532D]' => $report->status === 'published'])>{{ __(ucfirst($report->status)) }}</span>@if ($report->featured)<span class="ml-1 rounded-full bg-[#FEF3C7] px-2 py-1 text-xs font-semibold text-[#854D0E]">{{ __('Featured') }}</span>@endif</td>
                        <td class="whitespace-nowrap px-5 py-4 text-[#344054]">{{ number_format($report->downloads) }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-right">
                            <a href="{{ route('admin.reports.edit', $report) }}" class="font-semibold text-[#14532D] hover:underline">{{ __('Edit') }}</a>
                            @if ($report->status === 'published' && $report->publication_date->lte(today()))
                                <a href="{{ route('reports.show', $report) }}" class="ml-3 font-semibold text-[#14532D] hover:underline">{{ __('View') }}</a>
                            @endif
                            <form method="POST" action="{{ route('admin.reports.destroy', $report) }}" class="ml-3 inline" onsubmit="return confirm('{{ __('Delete this report and its stored files?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-semibold text-red-700 hover:underline">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-[#617067]">{{ __('No reports match these filters.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($reports->hasPages())
        <div class="border-t border-[#E8EFEA] px-5 py-4">{{ $reports->links() }}</div>
    @endif
</div>
@endsection
