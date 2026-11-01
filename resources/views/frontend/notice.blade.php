@extends('layouts.app')

@php
    $isNepali = app()->getLocale() === 'ne';
    $noticeTitle = $isNepali ? ($notice->title_np ?: $notice->title_en) : ($notice->title_en ?: $notice->title_np);
    $noticeBody = $isNepali ? ($notice->body_np ?: $notice->body_en) : ($notice->body_en ?: $notice->body_np);
    $noticeGuidance = match (true) {
        $notice->category === 'savings' => [
            'title' => 'Savings planning',
            'items' => [
                'Choose a deposit amount and schedule that fit your budget and savings goal.',
                'Ask the cooperative which account options, balance rules, withdrawal conditions, and fees apply.',
                'Confirm the current interest rate and required documents directly with the cooperative.',
            ],
        ],
        $notice->category === 'loan' => [
            'title' => 'Borrowing responsibly',
            'items' => [
                'Borrow only for a clear purpose and choose repayments that fit your income and essential expenses.',
                'Before applying, ask about eligibility, required documents, fees, security requirements, and repayment schedule.',
                'Confirm the current interest rate and total repayment before accepting an offer.',
            ],
        ],
        \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($notice->title_en), 'membership') => [
            'title' => 'Keeping your membership details current',
            'items' => [
                'Share your current phone number and address with the cooperative office.',
                'Ask which identification or supporting documents are needed for the update.',
                'Confirm the changes have been recorded so future notices reach you.',
            ],
        ],
        default => [
            'title' => 'Understanding this notice',
            'items' => [
                'Contact the cooperative office to confirm how this announcement applies to you.',
                'Ask for current official forms, service terms, or dates before taking action.',
            ],
        ],
    };
@endphp

@section('title', $noticeTitle)

@section('content')
<article class="fade-in mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <a href="{{ route('notices') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#08713D] hover:underline">
        <span aria-hidden="true">←</span>
        {{ $isNepali ? 'सबै सूचना' : 'All notices' }}
    </a>

    <div class="fade-in mt-6 rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-10">
        <div class="flex flex-wrap items-center gap-3">
            <span class="rounded-full bg-[#EFF8F1] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#08713D]">{{ __(ucfirst($notice->category)) }}</span>
            @if ($notice->published_at)
                <time datetime="{{ $notice->published_at->toIso8601String() }}" class="text-sm text-[#617067]">{{ $notice->published_at->translatedFormat('M j, Y') }}</time>
            @endif
        </div>
        <h1 lang="{{ $isNepali ? 'ne' : 'en' }}" class="mt-5 text-3xl font-extrabold leading-tight text-[#182A22] sm:text-4xl {{ $isNepali ? 'font-[Noto_Sans_Devanagari]' : '' }}">{{ $noticeTitle }}</h1>
        @if ($noticeBody)
            <p lang="{{ $isNepali ? 'ne' : 'en' }}" class="mt-6 whitespace-pre-wrap text-base leading-8 text-[#344054] {{ $isNepali ? 'font-[Noto_Sans_Devanagari]' : '' }}">{{ $noticeBody }}</p>
        @endif
    </div>

    <section class="mt-8 rounded-3xl border border-[#DCE9DF] bg-[#EFF8F1] p-6 sm:p-8" aria-labelledby="notice-guidance-heading">
        <h2 id="notice-guidance-heading" class="text-xl font-bold text-[#04552F]">{{ __($noticeGuidance['title']) }}</h2>
        <ul class="mt-4 space-y-3 text-sm leading-6 text-[#344054] {{ $isNepali ? 'font-[Noto_Sans_Devanagari]' : '' }}">
            @foreach ($noticeGuidance['items'] as $guidanceItem)
                <li class="flex gap-3">
                    <span aria-hidden="true" class="mt-2 h-2 w-2 shrink-0 rounded-full bg-[#08713D]"></span>
                    <span>{{ __($guidanceItem) }}</span>
                </li>
            @endforeach
        </ul>
        <a href="{{ route('contact') }}" class="mt-6 inline-flex items-center gap-2 rounded-full bg-[#08713D] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#04552F] focus:outline-none focus:ring-2 focus:ring-[#08713D] focus:ring-offset-2">
            {{ __('Contact Office') }}
            <span aria-hidden="true">→</span>
        </a>
    </section>

    @if ($relatedNotices->isNotEmpty())
        <section class="mt-12" aria-labelledby="related-notices-heading">
            <h2 id="related-notices-heading" class="text-2xl font-bold text-[#182A22]">{{ $isNepali ? 'सम्बन्धित सूचनाहरू' : 'Related notices' }}</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($relatedNotices as $relatedNotice)
                    @php
                        $relatedTitle = $isNepali ? ($relatedNotice->title_np ?: $relatedNotice->title_en) : ($relatedNotice->title_en ?: $relatedNotice->title_np);
                        $relatedBody = $isNepali ? ($relatedNotice->body_np ?: $relatedNotice->body_en) : ($relatedNotice->body_en ?: $relatedNotice->body_np);
                    @endphp
                    <a lang="{{ $isNepali ? 'ne' : 'en' }}" href="{{ route('notices.show', $relatedNotice) }}" class="group rounded-2xl border border-[#DCE9DF] bg-white p-5 transition hover:border-[#08713D]/40 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#08713D] focus:ring-offset-2">
                        <span class="text-xs font-semibold uppercase tracking-wide text-[#08713D]">{{ __(ucfirst($relatedNotice->category)) }}</span>
                        <h3 class="mt-2 font-semibold text-[#182A22] group-hover:text-[#08713D] group-hover:underline">{{ $relatedTitle }}</h3>
                        <p class="mt-2 text-sm leading-6 text-[#617067]">{{ Str::limit(strip_tags($relatedBody), 100) }}</p>
                        <time @if ($relatedNotice->published_at) datetime="{{ $relatedNotice->published_at->toIso8601String() }}" @endif class="mt-4 block text-xs text-[#617067]">{{ $relatedNotice->published_at?->translatedFormat('M j, Y') }}</time>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</article>
@endsection
