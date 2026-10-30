@extends('layouts.app')

@section('title', __('Loan Services'))

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-10 text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Loans') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22]">{{ __('Financial support for member needs') }}</h1>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach([
            [__('Personal Loan'), __('Support for personal, family, and household financial needs.')],
            [__('Business Loan'), __('For productive enterprise and working capital requirements.')],
            [__('Agricultural Loan'), __('Loan support for farm operations and rural income generation.')],
            [__('Education Loan'), __('Assistance for education-related expenses and skills development.')],
            [__('Emergency Loan'), __('Responsive support for urgent and unforeseen member needs.')],
            [__('Other Products'), __('Customized cooperative loan solutions as approved by management.')]
        ] as $loan)
            <div class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm">
                <div class="mb-4 inline-flex rounded-2xl bg-[#EFF8F1] p-3 text-xl text-[#08713D]">🏦</div>
                <h2 class="text-xl font-semibold text-[#182A22]">{{ $loan[0] }}</h2>
                <p class="mt-3 text-[#617067]">{{ $loan[1] }}</p>
                <ul class="mt-5 space-y-2 text-sm text-[#617067]">
                    <li>{{ __('Purpose') }}: {{ __('Member-defined need') }}</li>
                    <li>{{ __('Repayment') }}: {{ __('Based on approved terms') }}</li>
                    <li>{{ __('Interest') }}: {{ __('Subject to official policy') }}</li>
                </ul>
                <a href="#" class="mt-6 inline-block rounded-full bg-[#FFD91A] px-5 py-2 text-sm font-semibold text-[#04552F]">{{ __('Request Information') }}</a>
            </div>
        @endforeach
    </div>
</section>
@endsection
