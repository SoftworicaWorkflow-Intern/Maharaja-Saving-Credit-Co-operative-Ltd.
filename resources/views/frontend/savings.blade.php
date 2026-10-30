@extends('layouts.app')

@section('title', __('Savings Services'))

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-10 text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Savings') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22]">{{ __('Member savings schemes') }}</h1>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach([
            [__('Regular Savings'), __('Regular savings options open to members for disciplined and accessible account growth.')],
            [__('Monthly Savings'), __('Systematic monthly contribution for planned member saving and financial habit building.')],
            [__('Recurring Deposit'), __('Structured deposit program with periodic savings and maturity-based returns.')],
            [__('Fixed Deposit'), __('Longer-term deposit schemes for members seeking secured capital growth.')],
            [__('Children’s Savings'), __('Savings plan designed to help younger members build early financial habits.')],
            [__('Special Savings Schemes'), __('Customized schemes created according to member needs and cooperative policies.')]
        ] as $product)
            <div class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm">
                <div class="mb-4 inline-flex rounded-2xl bg-[#EFF8F1] p-3 text-xl text-[#08713D]">💸</div>
                <h2 class="text-xl font-semibold text-[#182A22]">{{ $product[0] }}</h2>
                <p class="mt-3 text-[#617067]">{{ $product[1] }}</p>
                <ul class="mt-5 space-y-2 text-sm text-[#617067]">
                    <li>{{ __('Eligibility') }}: {{ __('Open to eligible members') }}</li>
                    <li>{{ __('Deposit frequency') }}: {{ __('As per scheme rules') }}</li>
                    <li>{{ __('Interest') }}: {{ __('As officially approved') }}</li>
                </ul>
                <a href="#" class="mt-6 inline-block rounded-full bg-[#08713D] px-5 py-2 text-sm font-semibold text-white">{{ __('Apply Now') }}</a>
            </div>
        @endforeach
    </div>
</section>
@endsection
