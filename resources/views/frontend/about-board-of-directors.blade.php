@extends('layouts.app')

@section('title', __('Board of Directors').' | Maharaj Bachat')

@section('content')
<section id="board-of-directors" class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
    <header class="fade-in mx-auto mb-10 max-w-3xl text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Cooperative leadership') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22] sm:text-5xl">{{ __('Board of Directors') }}</h1>
        <p class="mt-5 text-lg leading-8 text-[#617067]">{{ __('Meet the people guiding Maharaj Bachat.') }}</p>
    </header>

    <figure class="fade-in mx-auto max-w-4xl overflow-hidden rounded-3xl border border-[#E8E4C8] bg-[#FFFDF1] p-3 shadow-xl sm:p-6">
        <img
            src="{{ asset('images/board-of-directors.png') }}"
            alt="{{ __('Board of Directors of Maharaj Bachat') }}"
            fetchpriority="high"
            decoding="async"
            class="mx-auto h-auto max-h-[70rem] w-auto max-w-full rounded-2xl object-contain"
        >
    </figure>

</section>
@endsection
