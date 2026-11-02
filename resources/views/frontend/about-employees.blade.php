@extends('layouts.app')

@section('title', __('Employees').' | Maharaj Bachat')

@section('content')
<section id="employees" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <header class="fade-in mx-auto mb-10 max-w-3xl text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Our team') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22] sm:text-5xl">{{ __('Employees') }}</h1>
    </header>

    <figure class="fade-in mx-auto max-w-3xl overflow-hidden rounded-3xl border border-[#DCE9DF] bg-white p-3 shadow-lg shadow-[#14532D]/10 sm:p-5">
        <img
            src="{{ asset('images/executive-committee.png') }}"
            alt="{{ __('Executive Committee of Maharaj Bachat tatha Rin Sahakari Sanstha Ltd.') }}"
            class="mx-auto h-auto max-h-[75vh] w-full rounded-2xl object-contain"
            loading="lazy"
            decoding="async"
        >
        <figcaption class="px-3 py-4 text-center text-sm font-semibold text-[#14532D]">
            {{ __('Executive Committee') }}
        </figcaption>
    </figure>
</section>
@endsection
