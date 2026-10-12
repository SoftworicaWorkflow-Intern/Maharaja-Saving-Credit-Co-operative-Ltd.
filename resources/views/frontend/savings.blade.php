@extends('layouts.app')

@section('title', 'Savings Services')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-10 text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">Savings</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22]">Member savings schemes</h1>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach([
            ['Regular Savings', 'Regular savings options open to members for disciplined and accessible account growth.'],
            ['Monthly Savings', 'Systematic monthly contribution for planned member saving and financial habit building.'],
            ['Recurring Deposit', 'Structured deposit program with periodic savings and maturity-based returns.'],
            ['Fixed Deposit', 'Longer-term deposit schemes for members seeking secured capital growth.'],
            ['Children’s Savings', 'Savings plan designed to help younger members build early financial habits.'],
            ['Special Savings Schemes', 'Customized schemes created according to member needs and cooperative policies.']
        ] as $product)
            <div class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm">
                <div class="mb-4 inline-flex rounded-2xl bg-[#EFF8F1] p-3 text-xl text-[#08713D]">💸</div>
                <h2 class="text-xl font-semibold text-[#182A22]">{{ $product[0] }}</h2>
                <p class="mt-3 text-[#617067]">{{ $product[1] }}</p>
                <ul class="mt-5 space-y-2 text-sm text-[#617067]">
                    <li>Eligibility: Open to eligible members</li>
                    <li>Deposit frequency: As per scheme rules</li>
                    <li>Interest: As officially approved</li>
                </ul>
                <a href="#" class="mt-6 inline-block rounded-full bg-[#08713D] px-5 py-2 text-sm font-semibold text-white">Apply Now</a>
            </div>
        @endforeach
    </div>
</section>
@endsection
