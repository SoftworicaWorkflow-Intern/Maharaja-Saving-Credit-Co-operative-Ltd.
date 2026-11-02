@extends('layouts.app')

@php
    $reportTitle = $report->localizedTitle();
    $chapters = $report->annualReport->chapters;
    $financialStatistics = $report->annualReport->financialStatistics;
@endphp

@section('title', $report->seo_title ?: $reportTitle)
@section('meta-description', $report->seo_description ?: $report->localizedShortDescription())

@section('content')
<main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
    <nav aria-label="{{ __('Breadcrumb') }}" class="mb-7 text-sm text-[#617067] print:hidden">
        <ol class="flex flex-wrap items-center gap-2">
            <li><a href="{{ route('home') }}" class="hover:text-[#14532D] hover:underline">{{ __('Home') }}</a></li>
            <li aria-hidden="true">/</li>
            <li><a href="{{ route('reports.index') }}" class="hover:text-[#14532D] hover:underline">{{ __('Reports') }}</a></li>
            <li aria-hidden="true">/</li>
            <li><a href="{{ route('annual-reports.index') }}" class="hover:text-[#14532D] hover:underline">{{ __('Annual Reports') }}</a></li>
            <li aria-hidden="true">/</li>
            <li aria-current="page" class="max-w-full truncate text-[#344054]">{{ $report->fiscalYear->localizedLabel() }}</li>
        </ol>
    </nav>

    <article>
        <header class="overflow-hidden rounded-3xl border border-[#DCE9DF] bg-white shadow-sm">
            @if ($report->featured_image_path)
                <img src="{{ route('reports.image', $report) }}" alt="{{ __('Cover for :title', ['title' => $reportTitle]) }}" class="max-h-[34rem] w-full bg-[#F5F8F5] object-cover">
            @endif
            <div class="p-6 sm:p-9">
                <div class="flex flex-wrap items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="" class="h-14 w-14 rounded-full border border-[#DCE9DF] bg-white p-1">
                    <div>
                        <p class="text-sm font-bold text-[#14532D]">{{ __('Maharaj Bachat tatha Rin Sahakari Sanstha Ltd.') }}</p>
                        <p class="mt-1 text-xs text-[#617067]">{{ __('Annual institutional publication') }}</p>
                    </div>
                    <span class="ml-auto rounded-full bg-[#E7F4EB] px-4 py-2 text-sm font-bold text-[#14532D]">{{ $report->fiscalYear->localizedLabel() }}</span>
                </div>
                <h1 lang="{{ app()->getLocale() }}" class="mt-7 text-3xl font-extrabold leading-tight text-[#182A22] sm:text-4xl">{{ $reportTitle }}</h1>
                @if ($report->title_en && $report->title_np)
                    <p lang="{{ app()->getLocale() === 'ne' ? 'en' : 'ne' }}" class="mt-2 text-lg text-[#617067]">{{ app()->getLocale() === 'ne' ? $report->title_en : $report->title_np }}</p>
                @endif
                <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-sm text-[#617067]">
                    <span>{{ __('Published') }} <time datetime="{{ $report->publication_date->toDateString() }}">{{ $report->publication_date->translatedFormat('M j, Y') }}</time></span>
                    @if ($report->reference_number)
                        <span>{{ __('Reference') }}: {{ $report->reference_number }}</span>
                    @endif
                </div>
                <p lang="{{ app()->getLocale() }}" class="mt-6 max-w-4xl text-base leading-8 text-[#344054]">{{ $report->localizedShortDescription() }}</p>
                @if ($report->localizedContent('executive_summary'))
                    <section class="mt-7 rounded-2xl bg-[#F5F8F5] p-5 sm:p-6" aria-labelledby="annual-executive-summary">
                        <h2 id="annual-executive-summary" class="text-lg font-bold text-[#14532D]">{{ __('Executive Summary') }}</h2>
                        <p lang="{{ app()->getLocale() }}" class="mt-3 whitespace-pre-line text-sm leading-7 text-[#344054]">{{ $report->localizedContent('executive_summary') }}</p>
                    </section>
                @endif
                <div class="mt-7 flex flex-wrap gap-3 print:hidden">
                    <a href="{{ route('annual-reports.index') }}" class="inline-flex min-h-11 items-center rounded-xl border border-[#DCE9DF] px-5 py-3 text-sm font-semibold text-[#14532D] hover:bg-[#F5F8F5]">{{ __('Annual report archive') }}</a>
                    @if ($report->document_visibility === 'public')
                        <a href="{{ route('reports.download', $report) }}" class="inline-flex min-h-11 items-center rounded-xl bg-[#14532D] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#27804A]">{{ __('Download Annual Report PDF') }}</a>
                    @endif
                    <button type="button" onclick="window.print()" class="inline-flex min-h-11 items-center rounded-xl border border-[#DCE9DF] px-5 py-3 text-sm font-semibold text-[#14532D] hover:bg-[#F5F8F5]">{{ __('Print report') }}</button>
                </div>
            </div>
        </header>

        @if ($chapters->isNotEmpty())
            <nav aria-labelledby="annual-table-of-contents" class="mt-7 rounded-2xl border border-[#DCE9DF] bg-white p-6 shadow-sm print:hidden">
                <h2 id="annual-table-of-contents" class="text-xl font-bold text-[#14532D]">{{ __('In this report') }}</h2>
                <ol class="mt-4 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($chapters as $chapter)
                        <li><a href="#chapter-{{ $chapter->id }}" class="rounded-lg text-sm font-medium text-[#14532D] underline decoration-[#B6D8C0] underline-offset-4 hover:text-[#27804A]">{{ $chapter->localizedTitle() }}</a></li>
                    @endforeach
                </ol>
            </nav>
        @endif

        @if ($financialStatistics->isNotEmpty())
            <section class="mt-8 rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-8" aria-labelledby="annual-financial-summary">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Administrator-verified information') }}</p>
                <h2 id="annual-financial-summary" class="mt-2 text-2xl font-extrabold text-[#182A22]">{{ __('Financial performance') }}</h2>
                <p class="mt-2 text-sm text-[#617067]">{{ __('Only figures verified and explicitly approved for publication are displayed.') }}</p>
                <dl class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($financialStatistics as $statistic)
                        <div class="rounded-2xl bg-[#F5F8F5] p-5">
                            <dt class="text-sm font-semibold text-[#617067]">{{ $statistic->label() }}</dt>
                            <dd class="mt-2 text-2xl font-extrabold text-[#14532D]">{{ $statistic->formattedValue() }} <span class="text-sm font-semibold">{{ $statistic->unit() }}</span></dd>
                            @if ($statistic->comparison_value !== null)
                                <p class="mt-2 text-xs text-[#617067]">{{ __('Prior-year comparison') }}: {{ $statistic->formattedValue($statistic->comparison_value) }} {{ $statistic->unit() }}</p>
                            @endif
                        </div>
                    @endforeach
                </dl>
            </section>
        @endif

        @foreach ($chapters as $chapter)
            <section id="chapter-{{ $chapter->id }}" class="mt-7 scroll-mt-24 rounded-2xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-8">
                <h2 lang="{{ app()->getLocale() }}" class="text-xl font-bold text-[#14532D] sm:text-2xl">{{ $chapter->localizedTitle() }}</h2>
                @if ($chapter->title_en && $chapter->title_np)
                    <p lang="{{ app()->getLocale() === 'ne' ? 'en' : 'ne' }}" class="mt-1 text-sm text-[#617067]">{{ app()->getLocale() === 'ne' ? $chapter->title_en : $chapter->title_np }}</p>
                @endif
                @if ($chapter->image_path)
                    <img src="{{ route('annual-reports.chapters.image', [$report, $chapter]) }}" alt="{{ $chapter->localizedTitle() }}" loading="lazy" class="mt-5 max-h-[28rem] w-full rounded-xl bg-[#F5F8F5] object-cover">
                @endif
                <div lang="{{ app()->getLocale() }}" class="mt-5 whitespace-pre-line text-sm leading-8 text-[#344054]">{{ $chapter->localizedContent() }}</div>
            </section>
        @endforeach

        <div class="mt-8">
            <x-annual-financial-charts :charts="$financialCharts" />
        </div>

        @if ($report->document_visibility === 'public')
            <section class="mt-8 print:hidden" aria-labelledby="annual-pdf-heading">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                    <h2 id="annual-pdf-heading" class="text-xl font-bold text-[#182A22]">{{ __('Official PDF document') }}</h2>
                    <a href="{{ route('reports.document', $report) }}" target="_blank" rel="noopener" class="text-sm font-semibold text-[#14532D] underline underline-offset-2">{{ __('Open PDF in a new tab') }}</a>
                </div>
                <div class="overflow-hidden rounded-2xl border border-[#DCE9DF] bg-[#F5F8F5]">
                    <iframe src="{{ route('reports.document', $report) }}" title="{{ __('PDF preview: :title', ['title' => $reportTitle]) }}" class="h-[70vh] min-h-[32rem] w-full" loading="lazy"></iframe>
                </div>
            </section>
        @endif
    </article>

    @if ($relatedReports->isNotEmpty())
        <section class="mt-12 print:hidden" aria-labelledby="related-annual-reports-heading">
            <h2 id="related-annual-reports-heading" class="text-2xl font-extrabold text-[#182A22]">{{ __('Previous Annual Reports') }}</h2>
            <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($relatedReports as $relatedReport)
                    <x-annual-report-card :report="$relatedReport" />
                @endforeach
            </div>
        </section>
    @endif
</main>
@endsection
