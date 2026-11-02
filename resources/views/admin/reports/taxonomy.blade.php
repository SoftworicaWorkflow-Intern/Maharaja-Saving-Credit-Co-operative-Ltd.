@extends('layouts.admin')

@section('title', __('Manage Report Categories & Fiscal Years'))
@section('page-title', __('Reports Management'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.reports.index') }}" class="text-sm font-semibold text-[#14532D] hover:underline">← {{ __('Reports Dashboard') }}</a>
    <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">{{ __('Manage Categories & Fiscal Years') }}</h2>
    <p class="mt-1 text-sm text-[#617067]">{{ __('Keep report classification and fiscal year filters current.') }}</p>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#14532D]">{{ session('status') }}</div>
@endif
@foreach (['category', 'fiscal_year'] as $errorKey)
    @error($errorKey)
        <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">{{ $message }}</div>
    @enderror
@endforeach

<div class="grid items-start gap-6 xl:grid-cols-2">
    <section class="rounded-xl border border-[#DCE9DF] bg-white p-5 shadow-sm sm:p-6" aria-labelledby="categories-heading">
        <h3 id="categories-heading" class="text-lg font-bold text-[#182A22]">{{ __('Report Categories') }}</h3>
        <form method="POST" action="{{ route('admin.report-categories.store') }}" class="mt-4 grid gap-3 sm:grid-cols-2">
            @csrf
            <label class="text-sm font-semibold text-[#344054]">{{ __('Name (English)') }}<input name="name_en" required maxlength="100" value="{{ old('name_en') }}" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
            <label class="text-sm font-semibold text-[#344054]">{{ __('Name (Nepali)') }}<input name="name_np" maxlength="100" value="{{ old('name_np') }}" lang="ne" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
            <label class="text-sm font-semibold text-[#344054]">{{ __('Slug') }}<input name="slug" required maxlength="100" pattern="[A-Za-z0-9_-]+" value="{{ old('slug') }}" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
            <label class="text-sm font-semibold text-[#344054]">{{ __('Display Order') }}<input type="number" name="display_order" min="0" max="65535" required value="{{ old('display_order', 0) }}" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
            <label class="text-sm font-semibold text-[#344054] sm:col-span-2">{{ __('Description') }}<input name="description" maxlength="500" value="{{ old('description') }}" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
            <label class="flex items-center gap-2 text-sm text-[#344054]"><input type="checkbox" name="is_active" value="1" checked class="rounded border-[#B6C9BC] text-[#14532D]">{{ __('Active in public filters') }}</label>
            <button type="submit" class="rounded-lg bg-[#14532D] px-4 py-2 text-sm font-semibold text-white hover:bg-[#27804A]">{{ __('Add Category') }}</button>
        </form>
        @foreach (['name_en', 'name_np', 'slug', 'display_order', 'description'] as $field)
            @error($field)<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        @endforeach

        <div class="mt-6 space-y-3">
            @foreach ($categories as $category)
                <form method="POST" action="{{ route('admin.report-categories.update', $category) }}" class="rounded-lg border border-[#E8EFEA] p-3">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-2 sm:grid-cols-2">
                        <label class="text-xs font-semibold text-[#617067]">{{ __('Name (English)') }}<input name="name_en" required maxlength="100" value="{{ $category->name_en }}" class="mt-1 w-full rounded-md border border-[#DCE9DF] px-2 py-1.5 text-sm font-normal text-[#182A22]"></label>
                        <label class="text-xs font-semibold text-[#617067]">{{ __('Name (Nepali)') }}<input name="name_np" maxlength="100" value="{{ $category->name_np }}" lang="ne" class="mt-1 w-full rounded-md border border-[#DCE9DF] px-2 py-1.5 text-sm font-normal text-[#182A22]"></label>
                        <label class="text-xs font-semibold text-[#617067]">{{ __('Slug') }}<input name="slug" required maxlength="100" pattern="[A-Za-z0-9_-]+" value="{{ $category->slug }}" class="mt-1 w-full rounded-md border border-[#DCE9DF] px-2 py-1.5 text-sm font-normal text-[#182A22]"></label>
                        <label class="text-xs font-semibold text-[#617067]">{{ __('Display Order') }}<input type="number" name="display_order" min="0" max="65535" required value="{{ $category->display_order }}" class="mt-1 w-full rounded-md border border-[#DCE9DF] px-2 py-1.5 text-sm font-normal text-[#182A22]"></label>
                        <label class="text-xs font-semibold text-[#617067] sm:col-span-2">{{ __('Description') }}<input name="description" maxlength="500" value="{{ $category->description }}" class="mt-1 w-full rounded-md border border-[#DCE9DF] px-2 py-1.5 text-sm font-normal text-[#182A22]"></label>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                        <label class="flex items-center gap-2 text-xs text-[#47564B]"><input type="checkbox" name="is_active" value="1" @checked($category->is_active) class="rounded border-[#B6C9BC] text-[#14532D]">{{ __('Active') }} · {{ trans_choice(':count report|:count reports', $category->reports_count, ['count' => $category->reports_count]) }}</label>
                        <div class="flex gap-3">
                            <button type="submit" class="text-sm font-semibold text-[#14532D] hover:underline">{{ __('Save') }}</button>
                            @if ($category->reports_count === 0)
                                <button type="submit" form="delete-category-{{ $category->id }}" class="text-sm font-semibold text-red-700 hover:underline">{{ __('Delete') }}</button>
                            @endif
                        </div>
                    </div>
                </form>
                @if ($category->reports_count === 0)
                    <form id="delete-category-{{ $category->id }}" method="POST" action="{{ route('admin.report-categories.destroy', $category) }}" onsubmit="return confirm('{{ __('Delete this category?') }}')">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            @endforeach
        </div>
    </section>

    <section class="rounded-xl border border-[#DCE9DF] bg-white p-5 shadow-sm sm:p-6" aria-labelledby="fiscal-years-heading">
        <h3 id="fiscal-years-heading" class="text-lg font-bold text-[#182A22]">{{ __('Fiscal Years') }}</h3>
        <form method="POST" action="{{ route('admin.fiscal-years.store') }}" class="mt-4 grid gap-3 sm:grid-cols-[1fr_150px_auto] sm:items-end">
            @csrf
            <label class="text-sm font-semibold text-[#344054]">{{ __('Fiscal year (Nepali)') }}<input name="label" required maxlength="40" value="{{ old('label') }}" placeholder="२०८४/०८५" lang="ne" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
            <label class="text-sm font-semibold text-[#344054]">{{ __('Fiscal year (English)') }}<input name="label_en" required maxlength="40" value="{{ old('label_en') }}" placeholder="2084/85" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
            <label class="text-sm font-semibold text-[#344054]">{{ __('Sort order') }}<input type="number" name="display_order" min="0" max="65535" required value="{{ old('display_order', 0) }}" class="mt-1 w-full rounded-lg border border-[#DCE9DF] px-3 py-2 font-normal"></label>
            <button type="submit" class="rounded-lg bg-[#14532D] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#27804A]">{{ __('Add Year') }}</button>
        </form>
        @foreach (['label', 'label_en', 'display_order'] as $field)
            @error($field)<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
        @endforeach

        <div class="mt-6 space-y-3">
            @foreach ($fiscalYears as $fiscalYear)
                <form method="POST" action="{{ route('admin.fiscal-years.update', $fiscalYear) }}" class="flex flex-wrap items-end gap-3 rounded-lg border border-[#E8EFEA] p-3">
                    @csrf
                    @method('PUT')
                    <label class="min-w-36 flex-1 text-xs font-semibold text-[#617067]">{{ __('Fiscal year (Nepali)') }}<input name="label" required maxlength="40" value="{{ $fiscalYear->label }}" lang="ne" class="mt-1 w-full rounded-md border border-[#DCE9DF] px-2 py-1.5 text-sm font-normal text-[#182A22]"></label>
                    <label class="min-w-28 flex-1 text-xs font-semibold text-[#617067]">{{ __('Fiscal year (English)') }}<input name="label_en" required maxlength="40" value="{{ $fiscalYear->label_en }}" class="mt-1 w-full rounded-md border border-[#DCE9DF] px-2 py-1.5 text-sm font-normal text-[#182A22]"></label>
                    <label class="w-28 text-xs font-semibold text-[#617067]">{{ __('Sort order') }}<input type="number" name="display_order" min="0" max="65535" required value="{{ $fiscalYear->display_order }}" class="mt-1 w-full rounded-md border border-[#DCE9DF] px-2 py-1.5 text-sm font-normal text-[#182A22]"></label>
                    <label class="flex items-center gap-2 pb-2 text-xs text-[#47564B]"><input type="checkbox" name="is_active" value="1" @checked($fiscalYear->is_active) class="rounded border-[#B6C9BC] text-[#14532D]">{{ __('Active') }} · {{ $fiscalYear->reports_count }}</label>
                    <button type="submit" class="pb-2 text-sm font-semibold text-[#14532D] hover:underline">{{ __('Save') }}</button>
                    @if ($fiscalYear->reports_count === 0)
                        <button type="submit" form="delete-year-{{ $fiscalYear->id }}" class="pb-2 text-sm font-semibold text-red-700 hover:underline">{{ __('Delete') }}</button>
                    @endif
                </form>
                @if ($fiscalYear->reports_count === 0)
                    <form id="delete-year-{{ $fiscalYear->id }}" method="POST" action="{{ route('admin.fiscal-years.destroy', $fiscalYear) }}" onsubmit="return confirm('{{ __('Delete this fiscal year?') }}')">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            @endforeach
        </div>
    </section>
</div>
@endsection
