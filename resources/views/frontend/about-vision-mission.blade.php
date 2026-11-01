@extends('layouts.app')

@section('title', __('Vision & Mission').' | Maharaj Bachat')

@section('content')
<section id="vision-mission" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <header class="fade-in mx-auto mb-12 max-w-3xl text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Our direction') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22] sm:text-5xl">{{ __('Vision & Mission') }}</h1>
        <p class="mt-5 text-lg leading-8 text-[#617067]">{{ __('The purpose and future that guide our service to members and community.') }}</p>
    </header>

    <div class="mx-auto grid max-w-5xl gap-6 md:grid-cols-2">
        <article class="fade-in rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm sm:p-10">
            <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#EFF8F1] text-2xl text-[#08713D]" aria-hidden="true">◎</div>
            <h2 class="text-2xl font-bold text-[#182A22]">{{ __('Vision') }}</h2>
            <p class="mt-4 text-base leading-8 text-[#617067]">{{ __('A financially sound business organization based in the community with quality service.') }}</p>
        </article>

        <article class="fade-in rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm sm:p-10">
            <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FFF9D9] text-2xl text-[#806B00]" aria-hidden="true">✦</div>
            <h2 class="text-2xl font-bold text-[#182A22]">{{ __('Mission') }}</h2>
            <p class="mt-4 text-base leading-8 text-[#617067]">{{ __('To provide economic, social and cultural development through efficient financial leadership, efficient management, policy, method and technology, and by providing financial and non-financial services to the members and the community.') }}</p>
        </article>
    </div>
</section>
@endsection
