@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-10 text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">About the Cooperative</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22]">Serving members with trust and purpose</h1>
    </div>
    <div class="grid gap-8 lg:grid-cols-2">
        <div class="rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-[#182A22]">Our introduction</h2>
            <p class="mt-4 text-[#617067]">Maharaj Bachat tatha Rin Sahakari Sanstha Ltd. is committed to promoting savings discipline, responsible lending, and member development through cooperative principles and ethical service.</p>
            <p class="mt-4 text-[#617067]">The cooperative works to strengthen individuals and communities by creating practical, accessible financial services centered on trust, transparency, and member welfare.</p>
        </div>
        <div class="rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-[#182A22]">Mission &amp; vision</h2>
            <div class="mt-4 space-y-4 text-[#617067]">
                <div>
                    <h3 class="text-lg font-semibold text-[#182A22]">Mission</h3>
                    <p class="mt-2">To provide economic, social and cultural development through efficient financial leadership, efficient management, policy, method and technology, and by providing financial and non-financial services to the members and the community.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-[#182A22]">Vision</h3>
                    <p class="mt-2">A financially sound business organization based in the community with quality service.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10 grid gap-8 lg:grid-cols-3">
        <div class="rounded-3xl bg-[#EFF8F1] p-8">
            <h3 class="text-xl font-semibold text-[#182A22]">Core values</h3>
            <ul class="mt-4 space-y-2 text-[#617067]">
                <li>Member welfare</li>
                <li>Transparency</li>
                <li>Trust and accountability</li>
                <li>Community service</li>
            </ul>
        </div>
        <div class="rounded-3xl bg-[#EFF8F1] p-8">
            <h3 class="text-xl font-semibold text-[#182A22]">Objectives</h3>
            <ul class="mt-4 space-y-2 text-[#617067]">
                <li>Expand accessible savings</li>
                <li>Support productive lending</li>
                <li>Promote financial literacy</li>
                <li>Strengthen local ownership</li>
            </ul>
        </div>
        <div class="rounded-3xl bg-[#EFF8F1] p-8">
            <h3 class="text-xl font-semibold text-[#182A22]">Organizational structure</h3>
            <p class="mt-4 text-[#617067]">The cooperative is led by the board and management team in accordance with cooperative governance and member oversight.</p>
        </div>
    </div>
</section>
@endsection
