@extends('layouts.app')

@section('title', __($message['label']).' | Maharaj Bachat')

@section('content')
<article class="leadership-message-page mx-auto max-w-4xl px-4 py-10 font-['Inter'] sm:px-6 sm:py-16 lg:px-8">
    <a href="{{ route('home') }}#leadership-messages" class="mb-8 inline-flex rounded-full px-3 py-2 text-sm font-semibold text-[#08713D] hover:bg-[#EFF8F1] focus:outline-none focus:ring-2 focus:ring-[#08713D] print:hidden">
        <span aria-hidden="true">←</span>
        <span class="ml-2">{{ __('Back to leadership messages') }}</span>
    </a>

    <div class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-10 lg:p-14">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#08713D]">{{ __($message['label']) }}</p>
                <h1 class="mt-3 text-3xl font-extrabold leading-tight text-[#04552F] sm:text-4xl">{{ __($message['title']) }}</h1>
                <p class="mt-3 text-base font-semibold text-[#08713D]">{{ $message['name'] ?: __($message['designation']) }}</p>
            </div>
            <button type="button" data-print-page class="inline-flex shrink-0 items-center justify-center gap-2 rounded-full border border-[#B6D8C0] px-5 py-3 text-sm font-semibold text-[#04552F] transition hover:bg-[#EFF8F1] focus:outline-none focus:ring-2 focus:ring-[#08713D] focus:ring-offset-2 print:hidden">
                <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8V3h10v5M7 17H5a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2m-10-3h10v7H7v-7Z"/>
                </svg>
                {{ __('Print Message') }}
            </button>
        </div>

        @if ($message['has_portrait'])
            <img src="{{ route('leadership.portrait', $leader) }}" alt="{{ __('Portrait of :name', ['name' => $message['name'] ?: __($message['designation'])]) }}" class="mt-8 max-h-[28rem] w-full rounded-2xl object-cover shadow-md print:hidden">
        @endif

        <div class="mt-8 space-y-6 border-t border-[#DCE9DF] pt-8 text-base leading-8 text-[#344054] sm:text-lg sm:leading-9">
            @foreach ($message['paragraphs'] as $paragraph)
                <p>{{ __($paragraph) }}</p>
            @endforeach
            <p lang="ne" class="pt-2 font-semibold text-[#04552F]">{{ $message['closing'] }}</p>
        </div>
    </div>
</article>
@endsection
