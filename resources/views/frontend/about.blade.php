@extends('layouts.app')

@section('title', __('About Us'))

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-10 text-center fade-in">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('About the Cooperative') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22]">{{ __('About Maharaj Bachat') }}</h1>
    </div>

    <div class="mx-auto grid max-w-4xl gap-8">
        <section class="fade-in rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm sm:p-10">
            <h2 class="text-2xl font-bold text-[#182A22]">{{ __('Our introduction') }}</h2>
            <p class="mt-4 text-base leading-8 text-[#617067]">{{ __('Maharaj Bachat tatha Rin Sahakari Sanstha Ltd. is committed to promoting savings discipline, responsible lending, and member development through cooperative principles and ethical service.') }}</p>
            <p class="mt-4 text-base leading-8 text-[#617067]">{{ __('The cooperative works to strengthen individuals and communities by creating practical, accessible financial services centered on trust, transparency, and member welfare.') }}</p>
        </section>

        <div class="grid gap-6 sm:grid-cols-2">
            <section class="fade-in rounded-3xl bg-[#EFF8F1] p-8">
                <h2 class="text-xl font-semibold text-[#182A22]">{{ __('Core values') }}</h2>
                <ul class="mt-4 space-y-3 text-[#617067]">
                    <li>{{ __('Member welfare') }}</li>
                    <li>{{ __('Transparency') }}</li>
                    <li>{{ __('Trust and accountability') }}</li>
                    <li>{{ __('Community service') }}</li>
                </ul>
            </section>
            <section class="fade-in rounded-3xl bg-[#EFF8F1] p-8">
                <h2 class="text-xl font-semibold text-[#182A22]">{{ __('Objectives') }}</h2>
                <ul class="mt-4 space-y-3 text-[#617067]">
                    <li>{{ __('Expand accessible savings') }}</li>
                    <li>{{ __('Support productive lending') }}</li>
                    <li>{{ __('Promote financial literacy') }}</li>
                    <li>{{ __('Strengthen local ownership') }}</li>
                </ul>
            </section>
        </div>

        <section class="fade-in rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm sm:p-10">
            <h2 class="text-2xl font-bold text-[#182A22]">{{ __('Organizational structure') }}</h2>
            <p class="mt-4 leading-7 text-[#617067]">{{ __('The cooperative is led by the board and management team in accordance with cooperative governance and member oversight.') }}</p>
        </section>
    </div>
</section>
@endsection
