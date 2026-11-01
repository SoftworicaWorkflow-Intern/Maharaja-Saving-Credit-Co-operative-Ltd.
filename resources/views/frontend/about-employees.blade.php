@extends('layouts.app')

@section('title', __('Employees').' | Maharaj Bachat')

@section('content')
<section id="employees" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <header class="fade-in mx-auto mb-10 max-w-3xl text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Our team') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22] sm:text-5xl">{{ __('Employees') }}</h1>
    </header>

    <div class="fade-in mx-auto max-w-3xl rounded-3xl border border-[#DCE9DF] bg-white px-6 py-14 text-center shadow-sm sm:px-12">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#EFF8F1] text-[#08713D]" aria-hidden="true">
            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M14 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
            </svg>
        </div>
        <h2 class="mt-6 text-2xl font-bold text-[#182A22]">{{ __('Employee profiles coming soon') }}</h2>
        <p class="mx-auto mt-3 max-w-xl leading-7 text-[#617067]">{{ __('Employee details will be added here.') }}</p>
    </div>
</section>
@endsection
