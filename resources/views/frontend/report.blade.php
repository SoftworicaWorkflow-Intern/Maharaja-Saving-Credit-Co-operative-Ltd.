@extends('layouts.app')

@php
    $reportTitle = $report->localizedTitle();
    $reportDescription = $report->localizedDescription();
    $categoryRouteName = config('report_pages.routes.'.$report->category->slug)
        ?: 'reports.category.custom';
    $categoryRouteParameters = config('report_pages.routes.'.$report->category->slug) === null
        ? ['slug' => $report->category->slug]
        : [];
    $categoryUrl = route($categoryRouteName, $categoryRouteParameters);
    $categoryMetadataLabels = [
        'statement_type' => __('Statement type'),
        'reporting_period' => __('Reporting period'),
        'audit_type' => __('Audit type'),
        'auditor_name' => __('Auditor'),
        'audit_period' => __('Audit period'),
        'meeting_date' => __('Meeting date'),
        'meeting_type' => __('Meeting type'),
        'policy_number' => __('Policy number'),
        'version' => __('Version'),
        'effective_date' => __('Effective date'),
        'approval_date' => __('Approval date'),
        'is_superseded' => __('Superseded version'),
        'publication_type' => __('Publication type'),
    ];
    $categoryMetadataValueLabels = [
        'balance-sheet' => __('Balance Sheet'),
        'income-expenditure' => __('Income and Expenditure'),
        'profit-loss' => __('Profit and Loss'),
        'cash-flow' => __('Cash Flow'),
        'changes-in-equity' => __('Changes in Equity'),
        'external' => __('External'),
        'internal' => __('Internal'),
        'compliance' => __('Compliance review'),
        'annual-general' => __('Annual General Meeting'),
        'special-general' => __('Special General Meeting'),
        'board' => __('Board'),
        'general' => __('General meeting'),
        'agm' => __('AGM'),
        'committee' => __('Committee'),
        'special-publication' => __('Special publication'),
        'institutional-information' => __('Institutional information'),
        'research-study' => __('Research or study'),
        'awareness-material' => __('Member awareness material'),
        'community-development' => __('Community development'),
        'official-circular' => __('Official circular'),
        'other' => __('Other'),
    ];
    $contentSections = [
        ['key' => 'executive_summary', 'label' => 'Executive summary'],
        ['key' => 'highlights', 'label' => 'Report highlights'],
        ['key' => 'key_findings', 'label' => 'Key findings'],
        ['key' => 'achievements', 'label' => 'Institutional achievements'],
        ['key' => 'financial_summary', 'label' => 'Financial summary'],
        ['key' => 'observations', 'label' => 'Important observations'],
        ['key' => 'future_plans', 'label' => 'Future plans'],
    ];
@endphp

@section('title', $report->seo_title ?: $reportTitle)
@section('meta-description', $report->seo_description ?: $report->localizedShortDescription())

@section('content')
<main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
    <nav aria-label="{{ __('Breadcrumb') }}" class="mb-7 text-sm text-[#617067] print:hidden">
        <ol class="flex flex-wrap items-center gap-2">
            <li><a href="{{ route('home') }}" class="hover:text-[#14532D] hover:underline">{{ __('Home') }}</a></li>
            <li aria-hidden="true">/</li>
            <li><a href="{{ $categoryUrl }}" class="hover:text-[#14532D] hover:underline">{{ $report->category->localizedName() }}</a></li>
            <li aria-hidden="true">/</li>
            <li aria-current="page" class="max-w-full truncate text-[#344054]">{{ $reportTitle }}</li>
        </ol>
    </nav>

    <article>
        <header class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-9">
            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                <span class="rounded-full bg-[#E7F4EB] px-3 py-1.5 text-[#14532D]">{{ $report->category->localizedName() }}</span>
                <span class="rounded-full bg-[#F5F8F5] px-3 py-1.5 text-[#47564B]">{{ $report->fiscalYear->localizedLabel() }}</span>
                @if ($report->featured)
                    <span class="rounded-full bg-[#FEF3C7] px-3 py-1.5 text-[#854D0E]">{{ __('Featured') }}</span>
                @endif
            </div>
            <h1 lang="{{ app()->getLocale() }}" class="mt-5 text-3xl font-extrabold leading-tight text-[#182A22] sm:text-4xl">{{ $reportTitle }}</h1>
            @if ($report->title_en && $report->title_np)
                <p lang="{{ app()->getLocale() === 'ne' ? 'en' : 'ne' }}" class="mt-2 text-lg font-medium text-[#617067]">{{ app()->getLocale() === 'ne' ? $report->title_en : $report->title_np }}</p>
            @endif
            <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-[#617067]">
                <span>{{ __('Published') }} <time datetime="{{ $report->publication_date->toDateString() }}">{{ $report->publication_date->translatedFormat('M j, Y') }}</time></span>
                <span>{{ __('Fiscal year') }}: {{ $report->fiscalYear->localizedLabel() }}</span>
                @if ($report->published_by)
                    <span>{{ __('Published by') }}: {{ $report->published_by }}</span>
                @endif
                @if ($report->reference_number)
                    <span>{{ __('Reference') }}: {{ $report->reference_number }}</span>
                @endif
                @if ($report->document_visibility === 'public')
                    <span>{{ __('PDF document') }} · {{ $report->formattedFileSize() }}</span>
                    <span>{{ number_format($report->downloads) }} {{ __('downloads') }}</span>
                @endif
            </div>
            <p lang="{{ app()->getLocale() }}" class="mt-6 max-w-4xl text-base leading-8 text-[#344054]">{{ $report->localizedShortDescription() }}</p>
            @if ($reportDescription)
                <div lang="{{ app()->getLocale() }}" class="mt-4 max-w-4xl whitespace-pre-line text-sm leading-7 text-[#617067]">{{ $reportDescription }}</div>
            @endif
            @if ($report->category_metadata)
                <dl class="mt-6 grid gap-3 border-t border-[#E8EFEA] pt-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($report->category_metadata as $key => $value)
                        @if (array_key_exists($key, $categoryMetadataLabels) && ($value !== null && $value !== ''))
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wide text-[#617067]">{{ $categoryMetadataLabels[$key] }}</dt>
                                <dd class="mt-1 text-sm font-semibold text-[#344054]">
                                    @if ($key === 'is_superseded')
                                        {{ $value ? __('Yes') : __('No') }}
                                    @else
                                        {{ $categoryMetadataValueLabels[$value] ?? $value }}
                                    @endif
                                </dd>
                            </div>
                        @endif
                    @endforeach
                </dl>
            @endif
            <div class="mt-7 flex flex-wrap gap-3 print:hidden">
                <a href="{{ $categoryUrl }}" class="inline-flex min-h-11 items-center rounded-xl border border-[#DCE9DF] px-5 py-3 text-sm font-semibold text-[#14532D] transition hover:bg-[#F5F8F5]">{{ __('Back to :category', ['category' => $report->category->localizedName()]) }}</a>
                @if ($report->document_visibility === 'public')
                    <a href="{{ route('reports.download', $report) }}" class="inline-flex min-h-11 items-center gap-2 rounded-xl bg-[#14532D] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#27804A] focus:outline-none focus:ring-2 focus:ring-[#27804A] focus:ring-offset-2">
                        <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M5 17v3h14v-3"/>
                        </svg>
                        {{ __('Download PDF') }}
                    </a>
                @endif
                <button type="button" onclick="window.print()" class="inline-flex min-h-11 items-center rounded-xl border border-[#DCE9DF] px-5 py-3 text-sm font-semibold text-[#14532D] transition hover:bg-[#F5F8F5]">{{ __('Print report') }}</button>
            </div>
        </header>

        @foreach ($contentSections as $section)
            @php($sectionContent = $report->localizedContent($section['key']))
            @if ($sectionContent)
                <section class="mt-7 rounded-2xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="text-xl font-bold text-[#14532D]">{{ __($section['label']) }}</h2>
                    <div lang="{{ app()->getLocale() }}" class="mt-4 whitespace-pre-line text-sm leading-7 text-[#344054]">{{ $sectionContent }}</div>
                </section>
            @endif
        @endforeach

        @if ($report->document_visibility === 'public' && $report->attachments->isNotEmpty())
            <section class="mt-7 rounded-2xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-8 print:hidden" aria-labelledby="public-attachments-heading">
                <h2 id="public-attachments-heading" class="text-xl font-bold text-[#14532D]">{{ __('Related public documents') }}</h2>
                <ul class="mt-4 divide-y divide-[#E8EFEA]">
                    @foreach ($report->attachments as $attachment)
                        <li class="flex flex-wrap items-center justify-between gap-3 py-3">
                            <span class="text-sm font-medium text-[#344054]">{{ $attachment->original_name }} <span class="text-xs text-[#617067]">· {{ number_format(max(1, (int) ceil($attachment->file_size / 1024))) }} KB</span></span>
                            <a href="{{ route('reports.attachments.download', [$report, $attachment]) }}" class="text-sm font-semibold text-[#14532D] underline underline-offset-2">{{ __('Download') }}</a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($report->document_visibility === 'public')
            <section aria-labelledby="report-document-heading" class="mt-8 print:hidden">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                    <h2 id="report-document-heading" class="text-xl font-bold text-[#182A22]">{{ __('Report document') }}</h2>
                    <div class="flex flex-wrap items-center gap-4">
                        <button type="button" data-report-fullscreen="report-document-viewer" data-enter-label="{{ __('Full screen') }}" data-exit-label="{{ __('Exit full screen') }}" data-fullscreen-error="{{ __('Your browser could not open full-screen view.') }}" aria-controls="report-document-viewer" class="text-sm font-semibold text-[#14532D] underline underline-offset-2">{{ __('Full screen') }}</button>
                        <a href="{{ route('reports.document', $report) }}" target="_blank" rel="noopener" class="text-sm font-semibold text-[#14532D] underline underline-offset-2">{{ __('Open PDF in a new tab') }}</a>
                    </div>
                </div>
                <div id="report-document-viewer" class="overflow-hidden rounded-2xl border border-[#DCE9DF] bg-[#F5F8F5] shadow-sm">
                    <iframe src="{{ route('reports.document', $report) }}" title="{{ __('PDF preview: :title', ['title' => $reportTitle]) }}" class="h-[70vh] min-h-[32rem] w-full" loading="lazy" allowfullscreen></iframe>
                </div>
                <p data-report-fullscreen-status="report-document-viewer" role="status" aria-live="polite" class="mt-2 text-sm text-[#9B1C1C]"></p>
                <p class="mt-3 text-sm text-[#617067]">{{ __('Use the PDF viewer controls to navigate, zoom, and print. If the document does not display in your browser, download it instead.') }}</p>
            </section>
        @else
            <aside class="mt-8 rounded-2xl border border-[#E8DDA6] bg-[#FFFBE5] p-5 text-sm text-[#5F5A3D]">
                {{ __('The report description is public, but its document is restricted.') }}
            </aside>
        @endif
    </article>

    @if ($relatedReports->isNotEmpty())
        <section class="mt-12 print:hidden" aria-labelledby="related-reports-heading">
            <h2 id="related-reports-heading" class="text-2xl font-extrabold text-[#182A22]">{{ __('Related publications') }}</h2>
            <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($relatedReports as $relatedReport)
                    <x-report-card :report="$relatedReport" />
                @endforeach
            </div>
        </section>
    @endif
</main>
@endsection
