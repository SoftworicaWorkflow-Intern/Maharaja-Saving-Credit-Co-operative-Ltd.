@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">Member Dashboard</p>
            <h1 class="mt-3 text-3xl font-bold text-[#182A22]">Welcome back</h1>
        </div>
        <form method="POST" action="{{ route('logout') }}">@csrf <button type="submit" class="rounded-full bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white">Logout</button></form>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[#DCE9DF]">
            <div class="text-sm text-[#617067]">Savings Balance</div>
            <div class="mt-2 text-3xl font-bold text-[#08713D]">NPR 0.00</div>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[#DCE9DF]">
            <div class="text-sm text-[#617067]">Outstanding Loan</div>
            <div class="mt-2 text-3xl font-bold text-[#08713D]">NPR 0.00</div>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[#DCE9DF]">
            <div class="text-sm text-[#617067]">Account Status</div>
            <div class="mt-2 text-3xl font-bold text-[#08713D]">Active</div>
        </div>
    </div>
</section>
@endsection
