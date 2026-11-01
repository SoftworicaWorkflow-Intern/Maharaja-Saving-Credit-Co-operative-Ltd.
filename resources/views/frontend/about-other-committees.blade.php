@extends('layouts.app')

@section('title', __('Other Committees').' | Maharaj Bachat')

@section('content')
<section id="other-committees" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <header class="fade-in mx-auto mb-10 max-w-3xl text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Cooperative leadership') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22] sm:text-5xl">{{ __('Other Committees') }}</h1>
    </header>

    <div class="mx-auto grid max-w-5xl gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ([
            ['id' => 'accounts-committee', 'name' => __('Accounts Committee')],
            ['id' => 'loan-sub-committee', 'name' => __('Loan Sub-Committee')],
            ['id' => 'education-sub-committee', 'name' => __('Education Sub-Committee')],
            ['id' => 'women-committee', 'name' => __('Women Committee')],
            ['id' => 'service-committee', 'name' => __('Service Committee')],
        ] as $committee)
            <article id="{{ $committee['id'] }}" class="fade-in scroll-mt-24 rounded-2xl border border-[#DCE9DF] bg-white p-7 shadow-sm">
                <h2 class="text-lg font-semibold text-[#182A22]">{{ $committee['name'] }}</h2>
                @if ($committee['id'] === 'accounts-committee')
                    <img
                        src="{{ asset('images/accounts-committee.png') }}"
                        alt="{{ __('Accounts Committee poster') }}"
                        loading="lazy"
                        decoding="async"
                        class="mt-5 h-auto w-full rounded-xl object-contain"
                    >
                @endif
                @if ($committee['id'] === 'loan-sub-committee')
                    <img
                        src="{{ asset('images/loan-sub-committee.png') }}"
                        alt="{{ __('Loan Sub-Committee poster') }}"
                        loading="lazy"
                        decoding="async"
                        class="mt-5 h-auto w-full rounded-xl object-contain"
                    >
                @endif
                @if ($committee['id'] === 'education-sub-committee')
                    <img
                        src="{{ asset('images/education-sub-committee.png') }}"
                        alt="{{ __('Education Sub-Committee poster') }}"
                        loading="lazy"
                        decoding="async"
                        class="mt-5 h-auto w-full rounded-xl object-contain"
                    >
                @endif
            </article>
        @endforeach
    </div>
</section>
@endsection
