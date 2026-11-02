@extends('layouts.app')

@section('title', __($page['title']))
@section('meta-description', __($page['seo_description']))

@section('content')
<section class="relative overflow-hidden bg-[#14532D] text-white">
    <div aria-hidden="true" class="absolute -right-20 -top-24 h-80 w-80 rounded-full border-[36px] border-white/5"></div>
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-18 lg:px-8">
        <nav aria-label="{{ __('Breadcrumb') }}" class="mb-6 text-sm text-white/80">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="hover:text-[#FACC15] hover:underline">{{ __('Home') }}</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('reports.index') }}" class="hover:text-[#FACC15] hover:underline">{{ __('Reports') }}</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page">{{ $category->localizedName() }}</li>
            </ol>
        </nav>
        <div class="relative grid gap-8 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
            <div class="max-w-4xl">
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-[#FACC15]">{{ __($page['eyebrow']) }}</p>
                <h1 class="mt-3 text-3xl font-extrabold leading-tight sm:text-5xl">{{ __($page['title']) }}</h1>
                <p class="mt-5 max-w-3xl text-base leading-7 text-white/90 sm:text-lg">{{ __($page['description']) }}</p>
                <form method="GET" action="{{ route($categoryRouteName, $categoryRouteParameters) }}" class="mt-8 grid max-w-3xl gap-3 sm:grid-cols-[1fr_auto]">
                    <label for="category-report-search" class="sr-only">{{ __('Search this archive') }}</label>
                    <input id="category-report-search" type="search" name="search" maxlength="100" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('Search titles and keywords') }}" class="min-h-12 rounded-xl border border-white/30 bg-white px-4 text-sm text-[#182A22] placeholder:text-[#617067] focus:outline-none focus:ring-2 focus:ring-[#FACC15]">
                    @if (! empty($filters['fiscal_year']))
                        <input type="hidden" name="fiscal_year" value="{{ $filters['fiscal_year'] }}">
                    @endif
                    <button type="submit" class="min-h-12 rounded-xl bg-[#FACC15] px-6 py-3 text-sm font-bold text-[#14532D] transition hover:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-white">{{ __('Search this archive') }}</button>
                </form>
            </div>
            <div class="hidden rounded-3xl border border-white/15 bg-white/10 p-6 text-right backdrop-blur-sm sm:block">
                <p class="text-sm font-semibold text-white/75">{{ __('Published documents') }}</p>
                <p class="mt-2 text-4xl font-extrabold text-[#FACC15]">{{ number_format($category->published_reports_count) }}</p>
                <p class="mt-1 text-sm text-white/75">{{ __('available publications') }}</p>
            </div>
        </div>
    </div>
</section>

<main class="mx-auto max-w-7xl space-y-12 px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
    <section class="grid gap-6 rounded-3xl bg-[#F5F8F5] p-6 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-center sm:p-8" aria-labelledby="category-introduction-heading">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ $category->localizedName() }}</p>
            <h2 id="category-introduction-heading" class="mt-2 text-2xl font-extrabold text-[#182A22]">{{ __($page['intro_heading']) }}</h2>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-[#617067]">{{ __($page['intro']) }}</p>
        </div>
        <a href="{{ route('reports.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[#14532D] bg-white px-5 py-3 text-sm font-semibold text-[#14532D] transition hover:bg-[#EFF8F1]">{{ __('Browse all reports') }}</a>
    </section>

    <section aria-label="{{ __('About this archive') }}" class="grid gap-4 sm:grid-cols-2 {{ count($page['sections']) > 3 ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }}">
        @foreach ($page['sections'] as $index => $section)
            <article class="rounded-2xl border border-[#DCE9DF] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:p-6">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#E7F4EB] text-sm font-extrabold text-[#14532D]">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                <h3 class="mt-4 text-lg font-bold text-[#182A22]">{{ __($section['title']) }}</h3>
                <p class="mt-2 text-sm leading-6 text-[#617067]">{{ __($section['description']) }}</p>
            </article>
        @endforeach
    </section>

    @if ($statistics->isNotEmpty())
        <section class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-8" aria-labelledby="verified-statistics-heading">
            <div class="mb-5">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Published institutional data') }}</p>
                <h2 id="verified-statistics-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('Verified financial and progress statistics') }}</h2>
                <p class="mt-2 text-sm text-[#617067]">{{ __('Only administrator-approved figures associated with an active fiscal year are shown.') }}</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($statistics as $statistic)
                    <article class="rounded-2xl bg-[#F5F8F5] p-5">
                        <p class="text-sm font-semibold text-[#617067]">{{ $statistic->localizedLabel() }}</p>
                        <p class="mt-2 text-2xl font-extrabold text-[#14532D]">{{ number_format((float) $statistic->value, 2) }} <span class="text-base font-semibold">{{ $statistic->localizedUnit() }}</span></p>
                        <p class="mt-2 text-xs text-[#617067]">{{ __('Fiscal year') }}: {{ $statistic->fiscalYear->localizedLabel() }}</p>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if ($financialCharts->isNotEmpty())
        <x-annual-financial-charts :charts="$financialCharts" />
    @endif

    <section aria-labelledby="fiscal-year-archive-heading">
        <div class="mb-4">
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Browse the archive') }}</p>
            <h2 id="fiscal-year-archive-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('Browse by fiscal year') }}</h2>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach ($fiscalYears as $fiscalYear)
                <a href="{{ route($categoryRouteName, array_merge($categoryRouteParameters, $filters, ['fiscal_year' => $fiscalYear->id])) }}" @class([
                    'rounded-full border px-4 py-2 text-sm font-semibold transition',
                    'border-[#14532D] bg-[#14532D] text-white' => (string) ($filters['fiscal_year'] ?? '') === (string) $fiscalYear->id,
                    'border-[#DCE9DF] bg-white text-[#14532D] hover:bg-[#EFF8F1]' => (string) ($filters['fiscal_year'] ?? '') !== (string) $fiscalYear->id,
                ])>{{ $fiscalYear->localizedLabel() }}</a>
            @endforeach
        </div>
    </section>

    @if ($featuredReports->isNotEmpty())
        <section aria-labelledby="category-featured-heading">
            <div class="mb-5">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Selected publications') }}</p>
                <h2 id="category-featured-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('Featured :category', ['category' => $category->localizedName()]) }}</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredReports as $report)
                    <x-report-card :report="$report" />
                @endforeach
            </div>
        </section>
    @endif

    @if ($latestReports->isNotEmpty())
        <section aria-labelledby="category-latest-heading">
            <div class="mb-5">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Recently published') }}</p>
                <h2 id="category-latest-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('Latest :category', ['category' => $category->localizedName()]) }}</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($latestReports as $report)
                    <x-report-card :report="$report" />
                @endforeach
            </div>
        </section>
    @endif

    <section aria-labelledby="category-archive-heading">
        <div class="mb-5">
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Official publications') }}</p>
            <h2 id="category-archive-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __($page['archive_heading']) }}</h2>
        </div>
        <form method="GET" action="{{ route($categoryRouteName, $categoryRouteParameters) }}" class="mb-7 grid gap-3 rounded-2xl border border-[#DCE9DF] bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-4 lg:items-end">
            <div class="sm:col-span-2">
                <label for="archive-search" class="mb-1.5 block text-sm font-semibold text-[#344054]">{{ __('Search title or keywords') }}</label>
                <input id="archive-search" type="search" name="search" maxlength="100" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('Report title or keyword') }}" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:border-[#27804A] focus:outline-none focus:ring-2 focus:ring-[#27804A]/20">
            </div>
            <div>
                <label for="archive-fiscal-year" class="mb-1.5 block text-sm font-semibold text-[#344054]">{{ __('Fiscal year') }}</label>
                <select id="archive-fiscal-year" name="fiscal_year" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#27804A]/20">
                    <option value="">{{ __('All fiscal years') }}</option>
                    @foreach ($fiscalYears as $fiscalYear)
                        <option value="{{ $fiscalYear->id }}" @selected((string) ($filters['fiscal_year'] ?? '') === (string) $fiscalYear->id)>{{ $fiscalYear->localizedLabel() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="archive-sort" class="mb-1.5 block text-sm font-semibold text-[#344054]">{{ __('Sort by publication date') }}</label>
                <select id="archive-sort" name="sort" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-3 py-3 text-sm text-[#182A22]">
                    <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>{{ __('Newest first') }}</option>
                    <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>{{ __('Oldest first') }}</option>
                </select>
            </div>
            <button type="submit" class="min-h-11 rounded-xl bg-[#14532D] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#27804A] focus:outline-none focus:ring-2 focus:ring-[#27804A] focus:ring-offset-2">{{ __('Filter reports') }}</button>
            @if ($filters !== [])
                <a href="{{ route($categoryRouteName, $categoryRouteParameters) }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[#DCE9DF] px-5 py-3 text-sm font-semibold text-[#344054] transition hover:bg-[#F5F8F5]">{{ __('Clear filters') }}</a>
            @endif
        </form>

        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($reports as $report)
                <x-report-card :report="$report" />
            @empty
                <div class="rounded-2xl border border-dashed border-[#B6C9BC] bg-white px-6 py-14 text-center md:col-span-2 lg:col-span-3">
                    <svg aria-hidden="true" class="mx-auto h-12 w-12 text-[#27804A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6M8 13h8M8 17h8"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-bold text-[#182A22]">{{ __($page['empty_title']) }}</h3>
                    <p class="mt-2 text-sm text-[#617067]">{{ __($page['empty_description']) }}</p>
                </div>
            @endforelse
        </div>

        @if ($reports->hasPages())
            <nav aria-label="{{ __('Report pages') }}" class="mt-9">{{ $reports->links() }}</nav>
        @endif
    </section>
</main>
@endsection
