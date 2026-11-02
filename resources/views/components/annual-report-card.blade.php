@props(['report'])

<article class="group overflow-hidden rounded-2xl border border-[#DCE9DF] bg-white shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-lg">
    <a href="{{ route('annual-reports.show', $report) }}" class="block focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#27804A]">
        @if ($report->featured_image_path)
            <img src="{{ route('reports.image', $report) }}" alt="{{ __('Cover for :title', ['title' => $report->localizedTitle()]) }}" loading="lazy" class="h-52 w-full bg-[#F5F8F5] object-cover">
        @else
            <div class="flex h-52 items-center justify-center bg-[#F5F8F5] text-[#14532D]" aria-hidden="true">
                <svg class="h-16 w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3.75h7.5L19 8.25v12A1.75 1.75 0 0 1 17.25 22h-10.5A1.75 1.75 0 0 1 5 20.25V5.5a1.75 1.75 0 0 1 2-1.75Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 4v5h5M8 13h8m-8 4h8"/>
                </svg>
            </div>
        @endif
        <div class="p-5">
            <div class="flex items-center justify-between gap-3">
                <span class="rounded-full bg-[#E7F4EB] px-3 py-1 text-xs font-bold text-[#14532D]">{{ $report->fiscalYear->localizedLabel() }}</span>
                @if ($report->featured)
                    <span class="text-xs font-semibold text-[#854D0E]">{{ __('Featured') }}</span>
                @endif
            </div>
            <h3 lang="{{ app()->getLocale() }}" class="mt-4 text-lg font-bold leading-7 text-[#182A22] group-hover:text-[#14532D]">{{ $report->localizedTitle() }}</h3>
            @if ($report->title_en && $report->title_np)
                <p lang="{{ app()->getLocale() === 'ne' ? 'en' : 'ne' }}" class="mt-1 text-sm text-[#617067]">{{ app()->getLocale() === 'ne' ? $report->title_en : $report->title_np }}</p>
            @endif
            <p class="mt-3 line-clamp-3 text-sm leading-6 text-[#617067]">{{ $report->localizedShortDescription() }}</p>
            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-[#E8EFEA] pt-4">
                <time datetime="{{ $report->publication_date->toDateString() }}" class="text-xs text-[#617067]">{{ $report->publication_date->translatedFormat('M j, Y') }}</time>
                <span class="text-sm font-bold text-[#14532D]">{{ __('View Annual Report') }} <span aria-hidden="true">→</span></span>
            </div>
        </div>
    </a>
</article>
