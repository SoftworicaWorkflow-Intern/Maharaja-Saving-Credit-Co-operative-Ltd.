@props(['report'])

@php
    $isNepali = app()->getLocale() === 'ne';
    $reportUrl = $report->category->slug === 'annual-reports'
        ? route('annual-reports.show', $report)
        : route('reports.show', $report);
    $reportMetadata = $report->category_metadata ?? [];
    $metadataField = match ($report->category->slug) {
        'financial-reports' => ['statement_type', __('Statement type')],
        'audit-reports' => ['audit_type', __('Audit type')],
        'agm-reports', 'meeting-decisions' => ['meeting_date', __('Meeting date')],
        'policies-guidelines' => ['version', __('Version')],
        'other-publications' => ['publication_type', __('Publication type')],
        default => null,
    };
    if ($metadataField !== null && empty($reportMetadata[$metadataField[0]]) && $report->category->slug === 'policies-guidelines') {
        $metadataField = ['effective_date', __('Effective date')];
    }
    $metadataValue = $metadataField === null ? null : ($reportMetadata[$metadataField[0]] ?? null);
    $metadataValueLabels = [
        'balance-sheet' => __('Balance Sheet'),
        'income-expenditure' => __('Income and Expenditure'),
        'profit-loss' => __('Profit and Loss'),
        'cash-flow' => __('Cash Flow'),
        'changes-in-equity' => __('Changes in Equity'),
        'external' => __('External'),
        'internal' => __('Internal'),
        'compliance' => __('Compliance review'),
        'special-publication' => __('Special publication'),
        'institutional-information' => __('Institutional information'),
        'research-study' => __('Research or study'),
        'awareness-material' => __('Member awareness material'),
        'community-development' => __('Community development'),
        'official-circular' => __('Official circular'),
        'other' => __('Other'),
    ];
    if (is_string($metadataValue)) {
        $metadataValue = $metadataValueLabels[$metadataValue] ?? str($metadataValue)->replace('-', ' ')->title()->toString();
    }
    if ($report->category->slug === 'policies-guidelines'
        && empty($reportMetadata['version'])
        && empty($reportMetadata['effective_date'])
        && ($reportMetadata['is_superseded'] ?? false)) {
        $metadataField = ['is_superseded', __('Superseded version')];
        $metadataValue = __('Yes');
    }
    $secondaryMetadata = $report->category->slug === 'audit-reports'
        && filled($reportMetadata['auditor_name'] ?? null)
        ? [__('Auditor'), $reportMetadata['auditor_name']]
        : null;
@endphp

<article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-[#DCE9DF] bg-white shadow-sm transition duration-200 hover:-translate-y-1 hover:border-[#27804A]/50 hover:shadow-lg">
    <a href="{{ $reportUrl }}" class="relative block aspect-[16/9] overflow-hidden bg-[#EFF8F1]" aria-label="{{ __('View report: :title', ['title' => $report->localizedTitle()]) }}">
        @if ($report->featured_image_path)
            <img src="{{ route('reports.image', $report) }}" alt="" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
        @else
            <div class="flex h-full items-center justify-center">
                <svg aria-hidden="true" class="h-16 w-16 text-[#27804A]" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5.5h13l9 9v27H14a3 3 0 0 1-3-3v-30a3 3 0 0 1 3-3Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M27 5.5v10h9M17 25h16M17 31h16M17 37h10"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18h3"/>
                </svg>
                <span class="sr-only">{{ __('PDF report') }}</span>
            </div>
        @endif
        @if ($report->featured)
            <span class="absolute left-4 top-4 rounded-full bg-[#FACC15] px-3 py-1 text-xs font-bold text-[#14532D]">{{ __('Featured') }}</span>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-5 sm:p-6">
        <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
            <span class="rounded-full bg-[#E7F4EB] px-3 py-1 text-[#14532D]">{{ $report->category->localizedName() }}</span>
            <span class="rounded-full bg-[#F5F8F5] px-3 py-1 text-[#47564B]">{{ $report->fiscalYear->localizedLabel() }}</span>
        </div>
        <h3 lang="{{ $isNepali ? 'ne' : 'en' }}" class="mt-4 text-lg font-bold leading-7 text-[#182A22] group-hover:text-[#14532D]">
            <a href="{{ route('reports.show', $report) }}">{{ $report->localizedTitle() }}</a>
        </h3>
        @if ($report->title_en && $report->title_np && app()->getLocale() !== 'ne')
            <p lang="ne" class="mt-1 text-sm text-[#617067]">{{ $report->title_np }}</p>
        @endif
        <p lang="{{ $isNepali ? 'ne' : 'en' }}" class="mt-2 line-clamp-3 text-sm leading-6 text-[#617067]">{{ $report->localizedShortDescription() }}</p>
        <div class="mt-4 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-[#617067]">
            <time datetime="{{ $report->publication_date->toDateString() }}">{{ $report->publication_date->translatedFormat('M j, Y') }}</time>
            <span aria-hidden="true">·</span>
            <span>PDF · {{ $report->formattedFileSize() }}</span>
        </div>
        @if ($metadataValue !== null && $metadataValue !== '')
            <p class="mt-2 text-xs font-medium text-[#617067]">{{ $metadataField[1] }}: {{ $metadataValue }}</p>
        @endif
        @if ($secondaryMetadata !== null)
            <p class="mt-1 text-xs font-medium text-[#617067]">{{ $secondaryMetadata[0] }}: {{ $secondaryMetadata[1] }}</p>
        @endif
        <div class="mt-auto flex flex-wrap gap-2 pt-5">
            <a href="{{ route('reports.show', $report) }}" class="inline-flex min-h-10 flex-1 items-center justify-center rounded-lg border border-[#14532D] px-4 py-2 text-sm font-semibold text-[#14532D] transition hover:bg-[#EFF8F1] focus:outline-none focus:ring-2 focus:ring-[#27804A] focus:ring-offset-2">{{ __('View Report') }}</a>
            @if ($report->document_visibility === 'public')
                <a href="{{ route('reports.download', $report) }}" class="inline-flex min-h-10 flex-1 items-center justify-center gap-2 rounded-lg bg-[#14532D] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#27804A] focus:outline-none focus:ring-2 focus:ring-[#27804A] focus:ring-offset-2">
                    <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M5 17v3h14v-3"/>
                    </svg>
                    {{ __('Download PDF') }}
                </a>
            @endif
        </div>
    </div>
</article>
