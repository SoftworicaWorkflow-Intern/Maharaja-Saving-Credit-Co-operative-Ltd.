@extends('layouts.admin')

@section('title', __('Verified Report Statistics'))
@section('page-title', __('Reports Management'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.reports.index') }}" class="text-sm font-semibold text-[#14532D] hover:underline">← {{ __('Reports Dashboard') }}</a>
    <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">{{ __('Verified institutional statistics') }}</h2>
    <p class="mt-1 max-w-3xl text-sm text-[#617067]">{{ __('Enter only figures verified by the cooperative. Every published statistic is labeled with its fiscal year; no figures are calculated from incomplete records.') }}</p>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#14532D]">{{ session('status') }}</div>
@endif

<section class="mb-8 rounded-xl border border-[#DCE9DF] bg-white p-5 shadow-sm sm:p-6" aria-labelledby="create-statistic-heading">
    <h3 id="create-statistic-heading" class="text-lg font-bold text-[#182A22]">{{ __('Add verified statistic') }}</h3>
    <form method="POST" action="{{ route('admin.report-statistics.store') }}" class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @csrf
        <label class="text-sm font-semibold text-[#344054]">{{ __('Label (English)') }}<input name="label_en" required maxlength="150" value="{{ old('label_en') }}" placeholder="{{ __('Total members') }}" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
        <label class="text-sm font-semibold text-[#344054]">{{ __('Label (Nepali)') }}<input name="label_np" maxlength="150" value="{{ old('label_np') }}" lang="ne" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
        <label class="text-sm font-semibold text-[#344054]">{{ __('Fiscal year') }}<select name="fiscal_year_id" required class="mt-1 w-full rounded-lg border border-[#DCE9DF] bg-white px-3 py-2 font-normal"><option value="">{{ __('Select fiscal year') }}</option>@foreach ($fiscalYears as $fiscalYear)<option value="{{ $fiscalYear->id }}" @selected((string) old('fiscal_year_id') === (string) $fiscalYear->id)>{{ $fiscalYear->localizedLabel() }}</option>@endforeach</select></label>
        <label class="text-sm font-semibold text-[#344054]">{{ __('Verified value') }}<input type="number" name="value" required min="0" max="999999999999999999.99" step="0.01" value="{{ old('value') }}" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
        <label class="text-sm font-semibold text-[#344054]">{{ __('Unit (English)') }}<input name="unit_en" maxlength="40" value="{{ old('unit_en') }}" placeholder="NPR" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
        <label class="text-sm font-semibold text-[#344054]">{{ __('Unit (Nepali)') }}<input name="unit_np" maxlength="40" value="{{ old('unit_np') }}" lang="ne" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
        <label class="text-sm font-semibold text-[#344054]">{{ __('Display order') }}<input type="number" name="display_order" required min="0" max="65535" value="{{ old('display_order', 0) }}" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
        <label class="flex items-center gap-2 self-end pb-2 text-sm text-[#344054]"><input type="checkbox" name="is_published" value="1" @checked(old('is_published')) class="rounded border-[#B6C9BC] text-[#14532D]">{{ __('Publish publicly') }}</label>
        <button type="submit" class="rounded-lg bg-[#14532D] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#27804A] sm:col-span-2 xl:col-span-4">{{ __('Save statistic') }}</button>
    </form>
    @if ($errors->any())
        <ul role="alert" class="mt-4 list-inside list-disc text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</section>

<div class="space-y-4">
    @forelse ($statistics as $statistic)
        <form method="POST" action="{{ route('admin.report-statistics.update', $statistic) }}" class="rounded-xl border border-[#DCE9DF] bg-white p-5 shadow-sm">
            @csrf
            @method('PUT')
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <label class="text-sm font-semibold text-[#344054]">{{ __('Label (English)') }}<input name="label_en" required maxlength="150" value="{{ $statistic->label_en }}" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
                <label class="text-sm font-semibold text-[#344054]">{{ __('Label (Nepali)') }}<input name="label_np" maxlength="150" value="{{ $statistic->label_np }}" lang="ne" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
                <label class="text-sm font-semibold text-[#344054]">{{ __('Fiscal year') }}<select name="fiscal_year_id" required class="mt-1 w-full rounded-lg border border-[#DCE9DF] bg-white px-3 py-2 font-normal">@foreach ($fiscalYears as $fiscalYear)<option value="{{ $fiscalYear->id }}" @selected($statistic->fiscal_year_id === $fiscalYear->id)>{{ $fiscalYear->localizedLabel() }}</option>@endforeach</select></label>
                <label class="text-sm font-semibold text-[#344054]">{{ __('Verified value') }}<input type="number" name="value" required min="0" max="999999999999999999.99" step="0.01" value="{{ $statistic->value }}" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
                <label class="text-sm font-semibold text-[#344054]">{{ __('Unit (English)') }}<input name="unit_en" maxlength="40" value="{{ $statistic->unit_en }}" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
                <label class="text-sm font-semibold text-[#344054]">{{ __('Unit (Nepali)') }}<input name="unit_np" maxlength="40" value="{{ $statistic->unit_np }}" lang="ne" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
                <label class="text-sm font-semibold text-[#344054]">{{ __('Display order') }}<input type="number" name="display_order" required min="0" max="65535" value="{{ $statistic->display_order }}" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
                <label class="flex items-center gap-2 self-end pb-2 text-sm text-[#344054]"><input type="checkbox" name="is_published" value="1" @checked($statistic->is_published) class="rounded border-[#B6C9BC] text-[#14532D]">{{ __('Published') }}</label>
            </div>
            <div class="mt-4 flex flex-wrap items-center gap-4 border-t border-[#E8EFEA] pt-4">
                <button type="submit" class="text-sm font-semibold text-[#14532D] hover:underline">{{ __('Save changes') }}</button>
                <span class="text-xs text-[#617067]">{{ $statistic->fiscalYear->localizedLabel() }}</span>
                <button type="submit" form="delete-statistic-{{ $statistic->id }}" class="text-sm font-semibold text-red-700 hover:underline">{{ __('Delete') }}</button>
            </div>
        </form>
        <form id="delete-statistic-{{ $statistic->id }}" method="POST" action="{{ route('admin.report-statistics.destroy', $statistic) }}" onsubmit="return confirm('{{ __('Delete this statistic?') }}')">
            @csrf
            @method('DELETE')
        </form>
    @empty
        <p class="rounded-xl border border-dashed border-[#B6C9BC] bg-white p-8 text-center text-sm text-[#617067]">{{ __('No statistics have been entered.') }}</p>
    @endforelse
</div>

@if ($statistics->hasPages())
    <div class="mt-6">{{ $statistics->links() }}</div>
@endif
@endsection
