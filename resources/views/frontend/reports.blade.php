@extends('layouts.app')

@section('title', __('Institutional Reports & Publications'))
@section('meta-description', __('Explore the official publications, institutional progress, financial performance, audit information, and important decisions of Maharaj Bachat tatha Rin Sahakari Sanstha Ltd.'))

@section('content')
<section class="bg-[#14532D] text-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-18 lg:px-8">
        <nav aria-label="{{ __('Breadcrumb') }}" class="mb-6 text-sm text-white/80">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="hover:text-[#FACC15] hover:underline">{{ __('Home') }}</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page">{{ __('Reports') }}</li>
            </ol>
        </nav>
        <div class="max-w-4xl">
            <p class="text-sm font-bold uppercase tracking-[0.2em] text-[#FACC15]">{{ __('Official Publications') }}</p>
            <h1 class="mt-3 text-3xl font-extrabold leading-tight sm:text-5xl">{{ __('Institutional Reports & Publications') }}</h1>
            <p class="mt-5 max-w-3xl text-base leading-7 text-white/90 sm:text-lg">{{ __('Explore the official publications, institutional progress, financial performance, audit information, and important decisions of Maharaj Bachat tatha Rin Sahakari Sanstha Ltd.') }}</p>
        </div>
        <form method="GET" action="{{ route('reports.index') }}" class="mt-8 grid max-w-3xl gap-3 sm:grid-cols-[1fr_auto]">
            <label for="hero-report-search" class="sr-only">{{ __('Search report titles and keywords') }}</label>
            <input id="hero-report-search" type="search" name="search" maxlength="100" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('Search reports, publications, or keywords') }}" class="min-h-12 rounded-xl border border-white/30 bg-white px-4 text-sm text-[#182A22] placeholder:text-[#617067] focus:outline-none focus:ring-2 focus:ring-[#FACC15]">
            <button type="submit" class="min-h-12 rounded-xl bg-[#FACC15] px-6 py-3 text-sm font-bold text-[#14532D] transition hover:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-white">{{ __('Search publications') }}</button>
        </form>
    </div>
</section>

<main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
    <section aria-labelledby="report-category-overview-heading" class="mb-12">
        <div class="mb-5">
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Explore the collections') }}</p>
            <h2 id="report-category-overview-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('Reports by category') }}</h2>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($categories as $category)
                @php
                    $categoryRoute = config('report_pages.routes.'.$category->slug);
                    $categoryRouteName = $categoryRoute ?: 'reports.category.custom';
                    $categoryRouteParameters = $categoryRoute === null ? ['slug' => $category->slug] : [];
                    $categoryPage = config('report_pages.pages.'.$category->slug);
                    $categoryUrl = route($categoryRouteName, $categoryRouteParameters);
                    $categoryDescription = $categoryPage['description']
                        ?? ($category->description ?: ($category->slug === 'annual-reports'
                            ? __('Explore our annual achievements, financial performance, institutional activities, and development journey. Our annual reports reflect our commitment to transparency, accountability, and sustainable cooperative development.')
                            : __('Browse approved publications in this collection.')));
                @endphp
                <a href="{{ $categoryUrl }}" class="group flex min-h-44 flex-col rounded-2xl border border-[#DCE9DF] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-[#27804A]/50 hover:shadow-lg">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#E7F4EB] text-[#14532D]" aria-hidden="true">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6M8 13h8M8 17h8"/>
                        </svg>
                    </span>
                    <h3 class="mt-4 text-base font-bold text-[#182A22] group-hover:text-[#14532D]">{{ $category->localizedName() }}</h3>
                    <p class="mt-2 line-clamp-3 text-sm leading-6 text-[#617067]">{{ __($categoryDescription) }}</p>
                    <span class="mt-auto flex items-center justify-between gap-3 pt-4 text-xs font-semibold text-[#14532D]">
                        <span>{{ number_format($category->published_reports_count) }} {{ __('published') }}</span>
                        <span>{{ __('View collection') }} <span aria-hidden="true">→</span></span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    @if ($statistics->isNotEmpty())
        <section class="mb-12 rounded-3xl bg-[#F5F8F5] p-5 sm:p-8" aria-labelledby="institution-statistics-heading">
            <div class="mb-5">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Verified institutional information') }}</p>
                <h2 id="institution-statistics-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('Institutional highlights') }}</h2>
                <p class="mt-2 text-sm text-[#617067]">{{ __('Figures are published by administrators and shown with their reporting fiscal year.') }}</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($statistics as $statistic)
                    <article class="rounded-2xl border border-[#DCE9DF] bg-white p-5">
                        <p class="text-sm font-semibold text-[#617067]">{{ $statistic->localizedLabel() }}</p>
                        <p class="mt-2 text-2xl font-extrabold text-[#14532D]">{{ number_format((float) $statistic->value, 2) }} <span class="text-base font-semibold">{{ $statistic->localizedUnit() }}</span></p>
                        <p class="mt-2 text-xs text-[#617067]">{{ __('Fiscal year') }}: {{ $statistic->fiscalYear->localizedLabel() }}</p>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if ($financialCharts->isNotEmpty())
        <div class="mb-12">
            <x-annual-financial-charts :charts="$financialCharts" />
        </div>
    @else
        <section class="mb-12 rounded-3xl border border-[#DCE9DF] bg-[#F5F8F5] p-6 sm:p-8" aria-labelledby="report-financial-charts-heading">
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Verified annual figures') }}</p>
            <h2 id="report-financial-charts-heading" class="mt-2 text-2xl font-extrabold text-[#182A22]">{{ __('Yearly financial performance') }}</h2>
            <p class="mt-2 text-sm leading-6 text-[#617067]">{{ __('Financial charts will appear when verified figures are published in annual reports.') }}</p>
        </section>
    @endif

    @if ($featuredReports->isNotEmpty())
        <section aria-labelledby="featured-reports-heading" class="mb-12">
            <div class="mb-5">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Selected publications') }}</p>
                <h2 id="featured-reports-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('Featured reports') }}</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredReports as $report)
                    <x-report-card :report="$report" />
                @endforeach
            </div>
        </section>
    @endif

    @if ($latestReports->isNotEmpty())
        <section aria-labelledby="latest-reports-heading" class="mb-12">
            <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Recently published') }}</p>
                    <h2 id="latest-reports-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('Latest publications') }}</h2>
                </div>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($latestReports as $report)
                    <x-report-card :report="$report" />
                @endforeach
            </div>
        </section>
    @endif

    <section aria-labelledby="all-reports-heading">
        <div class="mb-5">
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Search the archive') }}</p>
            <h2 id="all-reports-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('All reports and publications') }}</h2>
        </div>
        <form method="GET" action="{{ route('reports.index') }}" class="mb-7 grid gap-3 rounded-2xl border border-[#DCE9DF] bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 xl:items-end">
            <div class="xl:col-span-2">
                <label for="report-search" class="mb-1.5 block text-sm font-semibold text-[#344054]">{{ __('Search title or keywords') }}</label>
                <input id="report-search" type="search" name="search" maxlength="100" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('Report title or keyword') }}" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:border-[#27804A] focus:outline-none focus:ring-2 focus:ring-[#27804A]/20">
            </div>
            <div>
                <label for="report-category" class="mb-1.5 block text-sm font-semibold text-[#344054]">{{ __('Category') }}</label>
                <select id="report-category" name="category" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#27804A]/20">
                    <option value="">{{ __('All categories') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->localizedName() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="report-fiscal-year" class="mb-1.5 block text-sm font-semibold text-[#344054]">{{ __('Fiscal year') }}</label>
                <select id="report-fiscal-year" name="fiscal_year" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#27804A]/20">
                    <option value="">{{ __('All fiscal years') }}</option>
                    @foreach ($fiscalYears as $fiscalYear)
                        <option value="{{ $fiscalYear->id }}" @selected((string) ($filters['fiscal_year'] ?? '') === (string) $fiscalYear->id)>{{ $fiscalYear->localizedLabel() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="published-from" class="mb-1.5 block text-sm font-semibold text-[#344054]">{{ __('Published from') }}</label>
                <input id="published-from" type="date" name="published_from" value="{{ $filters['published_from'] ?? '' }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-3 py-3 text-sm text-[#182A22]">
            </div>
            <div>
                <label for="published-to" class="mb-1.5 block text-sm font-semibold text-[#344054]">{{ __('Published to') }}</label>
                <input id="published-to" type="date" name="published_to" value="{{ $filters['published_to'] ?? '' }}" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-3 py-3 text-sm text-[#182A22]">
            </div>
            <div>
                <label for="report-sort" class="mb-1.5 block text-sm font-semibold text-[#344054]">{{ __('Sort by publication date') }}</label>
                <select id="report-sort" name="sort" class="w-full rounded-xl border border-[#DCE9DF] bg-white px-3 py-3 text-sm text-[#182A22]">
                    <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>{{ __('Newest first') }}</option>
                    <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>{{ __('Oldest first') }}</option>
                </select>
            </div>
            <button type="submit" class="min-h-11 rounded-xl bg-[#14532D] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#27804A] focus:outline-none focus:ring-2 focus:ring-[#27804A] focus:ring-offset-2">{{ __('Filter reports') }}</button>
            @if ($filters !== [])
                <a href="{{ route('reports.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[#DCE9DF] px-5 py-3 text-sm font-semibold text-[#344054] transition hover:bg-[#F5F8F5]">{{ __('Clear filters') }}</a>
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
                    <h3 class="mt-4 text-lg font-bold text-[#182A22]">{{ __('No reports found') }}</h3>
                    <p class="mt-2 text-sm text-[#617067]">{{ __('Try a different search term or adjust your filters.') }}</p>
                </div>
            @endforelse
        </div>

        @if ($reports->hasPages())
            <nav aria-label="{{ __('Report pages') }}" class="mt-9">{{ $reports->links() }}</nav>
        @endif
    </section>
</main>
@endsection
