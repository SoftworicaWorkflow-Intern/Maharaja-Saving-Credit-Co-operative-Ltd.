@extends('layouts.app')

@php
    $isNepali = app()->getLocale() === 'ne';
    $noticeTitle = $isNepali ? ($notice->title_np ?: $notice->title_en) : ($notice->title_en ?: $notice->title_np);
    $noticeBody = $isNepali ? ($notice->body_np ?: $notice->body_en) : ($notice->body_en ?: $notice->body_np);
@endphp

@section('title', $noticeTitle)

@section('content')
<article class="fade-in mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <a href="{{ route('notices') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#08713D] hover:underline">
        <span aria-hidden="true">←</span>
        {{ $isNepali ? 'सबै सूचना' : 'All notices' }}
    </a>

    <div class="fade-in mt-6 rounded-xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-10">
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
</article>
@endsection
