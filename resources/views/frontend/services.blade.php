@extends('layouts.app')

@section('title', __('Services') . ' · ' . __('Maharaj Bachat tatha Rin Sahakari Sanstha Ltd.'))

@section('content')
@php
    $locale = app()->getLocale();
@endphp

{{-- ============================================================
     1. SERVICES HERO SECTION
============================================================ --}}
<section class="relative overflow-hidden bg-[#04552F] text-white">
    {{-- Background image with dark green gradient overlay --}}
    <div class="absolute inset-0">
        <img src="{{ asset('images/cooperative-service.png') }}" alt="{{ __('Financial Services') }}" class="h-full w-full object-cover object-center opacity-25">
        <div class="absolute inset-0 bg-gradient-to-r from-[#033B22]/95 via-[#04552F]/90 to-[#08713D]/80"></div>
        {{-- Decorative glowing orbs --}}
        <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-[#FFD91A]/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 h-96 w-96 rounded-full bg-[#08713D]/30 blur-3xl pointer-events-none"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
        {{-- Breadcrumb --}}
        <nav aria-label="{{ __('Breadcrumb') }}" class="mb-6">
            <ol class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-200/90 sm:text-sm">
                <li>
                    <a href="{{ route('home') }}" class="transition hover:text-[#FFD91A]">{{ __('Home') }}</a>
                </li>
                <li aria-hidden="true" class="text-emerald-400">/</li>
                <li aria-current="page" class="text-[#FFD91A] font-bold">
                    {{ __('Services') }}
                </li>
            </ol>
        </nav>

        <div class="max-w-3xl">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-1 text-xs font-bold uppercase tracking-[0.2em] text-[#FFD91A] backdrop-blur-sm">
                {{ __('Cooperative Financial Solutions') }}
            </span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                {{ __('Financial Services for a Better Tomorrow') }}
            </h1>
            <p class="mt-5 text-base leading-relaxed text-emerald-100 sm:text-lg">
                {{ __('Discover reliable savings, accessible loan facilities, and member-focused financial services designed to support your financial growth.') }}
            </p>

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="#savings" class="inline-flex items-center gap-2 rounded-full bg-[#FFD91A] px-6 py-3 text-sm font-bold text-[#04552F] shadow-md transition hover:bg-[#ffe14d] focus:outline-none focus:ring-2 focus:ring-[#FFD91A] focus:ring-offset-2 focus:ring-offset-[#04552F]">
                    <span>{{ __('Explore Savings') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                    </svg>
                </a>
                <a href="#loans" class="inline-flex items-center gap-2 rounded-full border border-white/40 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white hover:text-[#04552F] focus:outline-none focus:ring-2 focus:ring-white">
                    <span>{{ __('Loan Facilities') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                    </svg>
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-bold text-[#04552F] shadow-sm transition hover:bg-[#EFF8F1]">
                    <span>{{ __('Become a Member') }}</span>
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     2. SERVICES OVERVIEW (3 Quick Navigation Cards)
============================================================ --}}
<section class="fade-in -mt-8 relative z-20 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="grid gap-6 md:grid-cols-3">
        {{-- Card 1: Savings --}}
        <a href="#savings" class="group flex flex-col justify-between rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-[#08713D]/40 hover:shadow-2xl sm:p-8">
            <div>
                <div class="mb-5 flex items-center justify-between">
                    <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[#EFF8F1] text-3xl shadow-inner transition group-hover:scale-110">
                        💰
                    </span>
                    <span class="rounded-full bg-[#08713D] px-3 py-1 text-xs font-bold text-white uppercase tracking-wider">
                        {{ $savings->count() }} {{ __('Plans') }}
                    </span>
                </div>
                <h3 class="text-xl font-bold tracking-tight text-[#182A22] sm:text-2xl group-hover:text-[#08713D] transition">
                    {{ __('Savings Services') }}
                </h3>
                <p class="mt-3 text-sm leading-relaxed text-[#617067]">
                    {{ __('Disciplined regular accounts, high-yield fixed terms, and children’s welfare plans built for stability.') }}
                </p>
            </div>
            <div class="mt-6 flex items-center justify-between border-t border-[#DCE9DF] pt-4 text-xs font-bold uppercase tracking-wider text-[#08713D]">
                <span>{{ __('Jump to Savings') }}</span>
                <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                </svg>
            </div>
        </a>

        {{-- Card 2: Loans --}}
        <a href="#loans" class="group flex flex-col justify-between rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-[#08713D]/40 hover:shadow-2xl sm:p-8">
            <div>
                <div class="mb-5 flex items-center justify-between">
                    <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[#EFF8F1] text-3xl shadow-inner transition group-hover:scale-110">
                        🏦
                    </span>
                    <span class="rounded-full bg-[#08713D] px-3 py-1 text-xs font-bold text-white uppercase tracking-wider">
                        {{ $loans->count() }} {{ __('Schemes') }}
                    </span>
                </div>
                <h3 class="text-xl font-bold tracking-tight text-[#182A22] sm:text-2xl group-hover:text-[#08713D] transition">
                    {{ __('Loan Services') }}
                </h3>
                <p class="mt-3 text-sm leading-relaxed text-[#617067]">
                    {{ __('Responsible, transparent credit for business expansion, agriculture, emergency relief, and education.') }}
                </p>
            </div>
            <div class="mt-6 flex items-center justify-between border-t border-[#DCE9DF] pt-4 text-xs font-bold uppercase tracking-wider text-[#08713D]">
                <span>{{ __('Jump to Loans') }}</span>
                <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                </svg>
            </div>
        </a>

        {{-- Card 3: Member Support --}}
        <a href="#support" class="group flex flex-col justify-between rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-[#08713D]/40 hover:shadow-2xl sm:p-8">
            <div>
                <div class="mb-5 flex items-center justify-between">
                    <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[#EFF8F1] text-3xl shadow-inner transition group-hover:scale-110">
                        🤝
                    </span>
                    <span class="rounded-full bg-[#08713D] px-3 py-1 text-xs font-bold text-white uppercase tracking-wider">
                        {{ $memberSupport->count() }} {{ __('Services') }}
                    </span>
                </div>
                <h3 class="text-xl font-bold tracking-tight text-[#182A22] sm:text-2xl group-hover:text-[#08713D] transition">
                    {{ __('Member Support Services') }}
                </h3>
                <p class="mt-3 text-sm leading-relaxed text-[#617067]">
                    {{ __('Mini-banking counters, utility settlement, domestic remittance, and cooperative member welfare relief.') }}
                </p>
            </div>
            <div class="mt-6 flex items-center justify-between border-t border-[#DCE9DF] pt-4 text-xs font-bold uppercase tracking-wider text-[#08713D]">
                <span>{{ __('Jump to Member Support') }}</span>
                <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                </svg>
            </div>
        </a>
    </div>
</section>

{{-- ============================================================
     3. SAVINGS SERVICES SECTION
============================================================ --}}
<section id="savings" class="fade-in scroll-mt-20 py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-14 text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#DCE9DF] bg-white px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] text-[#08713D] shadow-sm">
                {{ __('Savings Products') }}
            </span>
            <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-[#04552F] sm:text-4xl lg:text-5xl">
                {{ __('Secure Savings, Stronger Returns') }}
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-base text-[#617067] sm:text-lg">
                {{ __('Choose from institutional savings schemes structured to safeguard your deposits while providing reliable, daily-calculated interest returns.') }}
            </p>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse($savings as $service)
                <article class="flex flex-col justify-between rounded-3xl border border-[#DCE9DF] bg-white p-7 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[#08713D]/40 hover:shadow-xl">
                    <div>
                        <div class="mb-5 flex items-center justify-between">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#EFF8F1] text-2xl text-[#08713D] shadow-inner">
                                @if($service->slug === 'regular-savings') 💵
                                @elseif($service->slug === 'monthly-savings') 📅
                                @elseif($service->slug === 'children-savings') 🎓
                                @elseif($service->slug === 'special-savings') ✨
                                @elseif($service->slug === 'fixed-deposit') 🔒
                                @else 💰
                                @endif
                            </span>
                            <span class="rounded-full bg-[#EFF8F1] px-3 py-1 text-xs font-bold text-[#08713D] uppercase tracking-wider">
                                {{ __('Savings') }}
                            </span>
                        </div>

                        <h3 class="text-xl font-bold tracking-tight text-[#182A22]">
                            <a href="{{ route('services.show', $service) }}" class="transition hover:text-[#08713D] hover:underline">
                                {{ $service->title() }}
                            </a>
                        </h3>

                        <p class="mt-3 text-sm leading-relaxed text-[#617067]">
                            {{ $service->shortDescription() }}
                        </p>

                        {{-- Key Benefits list --}}
                        @if(!empty($service->benefits()))
                            <div class="mt-6 border-t border-[#DCE9DF] pt-4">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[#08713D]">{{ __('Key Benefits') }}</h4>
                                <ul class="mt-2.5 space-y-2 text-xs text-[#344054]">
                                    @foreach(array_slice($service->benefits(), 0, 3) as $benefit)
                                        <li class="flex items-start gap-2">
                                            <svg class="h-4 w-4 shrink-0 text-[#08713D] mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>{{ $benefit }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Eligibility note --}}
                        @if($service->eligibility())
                            <div class="mt-4 rounded-xl bg-[#EFF8F1] p-3 text-[11px] leading-relaxed text-[#04552F]">
                                <span class="font-bold">{{ __('Eligibility') }}:</span> {{ Str::limit($service->eligibility(), 90) }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex items-center justify-between border-t border-[#DCE9DF] pt-4">
                        <a href="{{ route('services.show', $service) }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#08713D] transition hover:text-[#04552F] hover:underline">
                            <span>{{ __('View Details') }}</span>
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('register') }}" class="rounded-full bg-[#FFD91A] px-3.5 py-1.5 text-xs font-bold text-[#04552F] shadow-sm transition hover:bg-[#ffe14d]">
                            {{ __('Open Account') }}
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-[#DCE9DF] bg-white p-10 text-center text-sm text-[#617067]">
                    {{ __('No savings services listed at the moment.') }}
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ============================================================
     4. LOAN SERVICES SECTION
============================================================ --}}
<section id="loans" class="fade-in scroll-mt-20 border-y border-[#DCE9DF] bg-[#EFF8F1] py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-14 text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#DCE9DF] bg-white px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] text-[#08713D] shadow-sm">
                {{ __('Credit Facilities') }}
            </span>
            <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-[#04552F] sm:text-4xl lg:text-5xl">
                {{ __('Accessible & Responsible Loan Support') }}
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-base text-[#617067] sm:text-lg">
                {{ __('Transparent credit options designed to foster local enterprise, agricultural growth, career education, and urgent family relief.') }}
            </p>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse($loans as $service)
                <article class="flex flex-col justify-between rounded-3xl border border-[#DCE9DF] bg-white p-7 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[#08713D]/40 hover:shadow-xl">
                    <div>
                        <div class="mb-5 flex items-center justify-between">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#EFF8F1] text-2xl text-[#08713D] shadow-inner">
                                @if($service->slug === 'personal-loan') 🏠
                                @elseif($service->slug === 'business-loan') 💼
                                @elseif($service->slug === 'agricultural-loan') 🌱
                                @elseif($service->slug === 'educational-loan') 📖
                                @elseif($service->slug === 'emergency-loan') 🚨
                                @else 🏦
                                @endif
                            </span>
                            <span class="rounded-full bg-[#08713D] px-3 py-1 text-xs font-bold text-white uppercase tracking-wider">
                                {{ __('Loan Scheme') }}
                            </span>
                        </div>

                        <h3 class="text-xl font-bold tracking-tight text-[#182A22]">
                            <a href="{{ route('services.show', $service) }}" class="transition hover:text-[#08713D] hover:underline">
                                {{ $service->title() }}
                            </a>
                        </h3>

                        <p class="mt-3 text-sm leading-relaxed text-[#617067]">
                            {{ $service->shortDescription() }}
                        </p>

                        {{-- Purpose / Eligibility box --}}
                        <div class="mt-5 space-y-2.5 rounded-2xl bg-[#EFF8F1]/60 p-4 text-xs">
                            @if($service->purpose())
                                <div>
                                    <span class="font-bold text-[#04552F]">{{ __('Purpose') }}:</span>
                                    <span class="text-[#344054]">{{ $service->purpose() }}</span>
                                </div>
                            @endif
                            @if($service->eligibility())
                                <div>
                                    <span class="font-bold text-[#04552F]">{{ __('Eligibility') }}:</span>
                                    <span class="text-[#344054]">{{ Str::limit($service->eligibility(), 100) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Required documents preview --}}
                        @if(!empty($service->requiredDocuments()))
                            <div class="mt-5 border-t border-[#DCE9DF] pt-4">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-[#08713D]">{{ __('Key Documents') }}</h4>
                                <ul class="mt-2 space-y-1.5 text-xs text-[#617067]">
                                    @foreach(array_slice($service->requiredDocuments(), 0, 3) as $doc)
                                        <li class="flex items-start gap-2">
                                            <span class="h-1.5 w-1.5 rounded-full bg-[#08713D] mt-1.5 shrink-0"></span>
                                            <span>{{ $doc }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex items-center justify-between border-t border-[#DCE9DF] pt-4">
                        <a href="{{ route('services.show', $service) }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#08713D] transition hover:text-[#04552F] hover:underline">
                            <span>{{ __('View Details') }}</span>
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('contact') }}" class="rounded-full bg-[#08713D] px-3.5 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#04552F]">
                            {{ __('Inquire Loan') }}
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-[#DCE9DF] bg-white p-10 text-center text-sm text-[#617067]">
                    {{ __('No loan services listed at the moment.') }}
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ============================================================
     5. MEMBER SUPPORT SERVICES
============================================================ --}}
<section id="support" class="fade-in scroll-mt-20 py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-14 text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#DCE9DF] bg-white px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] text-[#08713D] shadow-sm">
                {{ __('Value Added Services') }}
            </span>
            <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-[#04552F] sm:text-4xl lg:text-5xl">
                {{ __('Member Support & Welfare Facilities') }}
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-base text-[#617067] sm:text-lg">
                {{ __('Beyond banking, our cooperative offers day-to-day transaction convenience, utility settlement, and institutional welfare relief for members and their families.') }}
            </p>
        </div>

        <div class="grid gap-8 md:grid-cols-3">
            @forelse($memberSupport as $service)
                <article class="flex flex-col justify-between rounded-3xl border border-[#DCE9DF] bg-white p-7 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-[#08713D]/40 hover:shadow-xl">
                    <div>
                        <div class="mb-5 flex items-center justify-between">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#EFF8F1] text-2xl text-[#08713D] shadow-inner">
                                @if($service->slug === 'mini-banking-services') 🏛️
                                @elseif($service->slug === 'remittance-utility-services') 📲
                                @elseif($service->slug === 'member-welfare-guidance') 🤝
                                @else 🌟
                                @endif
                            </span>
                            <span class="rounded-full bg-[#EFF8F1] px-3 py-1 text-xs font-bold text-[#08713D] uppercase tracking-wider">
                                {{ __('Support') }}
                            </span>
                        </div>

                        <h3 class="text-xl font-bold tracking-tight text-[#182A22]">
                            <a href="{{ route('services.show', $service) }}" class="transition hover:text-[#08713D] hover:underline">
                                {{ $service->title() }}
                            </a>
                        </h3>

                        <p class="mt-3 text-sm leading-relaxed text-[#617067]">
                            {{ $service->shortDescription() }}
                        </p>

                        @if(!empty($service->benefits()))
                            <ul class="mt-5 space-y-2 border-t border-[#DCE9DF] pt-4 text-xs text-[#344054]">
                                @foreach(array_slice($service->benefits(), 0, 3) as $benefit)
                                    <li class="flex items-start gap-2">
                                        <svg class="h-4 w-4 shrink-0 text-[#08713D] mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $benefit }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="mt-6 border-t border-[#DCE9DF] pt-4">
                        <a href="{{ route('services.show', $service) }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#08713D] transition hover:text-[#04552F] hover:underline">
                            <span>{{ __('View Details') }}</span>
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-[#DCE9DF] bg-white p-10 text-center text-sm text-[#617067]">
                    {{ __('No support services listed at the moment.') }}
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ============================================================
     6. MEMBER BENEFITS SECTION (2-Column Layout)
============================================================ --}}
<section class="fade-in border-y border-[#DCE9DF] bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-[#DCE9DF] bg-[#EFF8F1] px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] text-[#08713D]">
                    {{ __('Member Benefits') }}
                </span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-[#04552F] sm:text-4xl">
                    {{ __('Built for Members, Owned by Members') }}
                </h2>
                <p class="mt-4 text-base leading-relaxed text-[#617067]">
                    {{ __('Unlike commercial banks that operate for outside shareholders, Maharaj Bachat is entirely owned by its community members. Every rupee deposited and every credit facility approved directly enriches local families.') }}
                </p>

                <div class="mt-8 grid gap-5 sm:grid-cols-2">
                    {{-- Benefit 1 --}}
                    <div class="flex items-start gap-3.5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EFF8F1] text-lg text-[#08713D]">🛡️</div>
                        <div>
                            <h3 class="text-sm font-bold text-[#182A22]">{{ __('Secure Savings Services') }}</h3>
                            <p class="mt-1 text-xs leading-relaxed text-[#617067]">{{ __('Regulated financial management with audited transparency.') }}</p>
                        </div>
                    </div>

                    {{-- Benefit 2 --}}
                    <div class="flex items-start gap-3.5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EFF8F1] text-lg text-[#08713D]">⚡</div>
                        <div>
                            <h3 class="text-sm font-bold text-[#182A22]">{{ __('Accessible Financial Support') }}</h3>
                            <p class="mt-1 text-xs leading-relaxed text-[#617067]">{{ __('Fair credit with friendly evaluation and fast disbursement.') }}</p>
                        </div>
                    </div>

                    {{-- Benefit 3 --}}
                    <div class="flex items-start gap-3.5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EFF8F1] text-lg text-[#08713D]">❤️</div>
                        <div>
                            <h3 class="text-sm font-bold text-[#182A22]">{{ __('Member-Focused Service') }}</h3>
                            <p class="mt-1 text-xs leading-relaxed text-[#617067]">{{ __('Respectful counter care and doorstep service for seniors.') }}</p>
                        </div>
                    </div>

                    {{-- Benefit 4 --}}
                    <div class="flex items-start gap-3.5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EFF8F1] text-lg text-[#08713D]">💡</div>
                        <div>
                            <h3 class="text-sm font-bold text-[#182A22]">{{ __('Financial Guidance') }}</h3>
                            <p class="mt-1 text-xs leading-relaxed text-[#617067]">{{ __('Free guidance for business growth and debt management.') }}</p>
                        </div>
                    </div>

                    {{-- Benefit 5 --}}
                    <div class="flex items-start gap-3.5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EFF8F1] text-lg text-[#08713D]">🌱</div>
                        <div>
                            <h3 class="text-sm font-bold text-[#182A22]">{{ __('Community Development') }}</h3>
                            <p class="mt-1 text-xs leading-relaxed text-[#617067]">{{ __('Portion of surplus reinvested in local health and education.') }}</p>
                        </div>
                    </div>

                    {{-- Benefit 6 --}}
                    <div class="flex items-start gap-3.5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EFF8F1] text-lg text-[#08713D]">🗳️</div>
                        <div>
                            <h3 class="text-sm font-bold text-[#182A22]">{{ __('Democratic Participation') }}</h3>
                            <p class="mt-1 text-xs leading-relaxed text-[#617067]">{{ __('Equal voting rights in AGM and dividend share distributions.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Illustrative Graphic / Image Card --}}
            <div class="relative overflow-hidden rounded-3xl border border-[#DCE9DF] bg-[#EFF8F1] p-8 shadow-sm lg:p-12">
                <div class="relative z-10">
                    <span class="inline-flex rounded-full bg-[#08713D] px-3.5 py-1 text-xs font-bold text-white uppercase tracking-wider">
                        {{ __('Cooperative Advantage') }}
                    </span>
                    <h3 class="mt-4 text-2xl font-bold text-[#04552F] sm:text-3xl">
                        {{ __('Annual Member Dividend & Surplus Sharing') }}
                    </h3>
                    <p class="mt-3 text-sm leading-relaxed text-[#344054]">
                        {{ __('As a member-shareholder, you enjoy financial dividends derived from our annual operational surplus. Your savings do not enrich external investors; they return directly to you.') }}
                    </p>

                    <div class="mt-8 space-y-3">
                        <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="text-2xl">📈</span>
                            <div>
                                <div class="text-xs font-bold text-[#04552F]">{{ __('Share Dividends (लाभांश)') }}</div>
                                <div class="text-[11px] text-[#617067]">{{ __('Approved annual return distributed to registered share accounts.') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 rounded-2xl bg-white p-4 shadow-sm">
                            <span class="text-2xl">🏥</span>
                            <div>
                                <div class="text-xs font-bold text-[#04552F]">{{ __('Member Relief Fund (राहत कोष)') }}</div>
                                <div class="text-[11px] text-[#617067]">{{ __('Immediate grant assistance in bereavement, illness, and child delivery.') }}</div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="mt-8 inline-flex items-center gap-2 rounded-full bg-[#08713D] px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-[#04552F]">
                        <span>{{ __('Join as Member Today') }}</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     7. HOW TO BECOME A MEMBER (4-Step Process)
============================================================ --}}
<section class="fade-in py-20 bg-[#EFF8F1]">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-14 text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#DCE9DF] bg-white px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] text-[#08713D] shadow-sm">
                {{ __('Membership Journey') }}
            </span>
            <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-[#04552F] sm:text-4xl lg:text-5xl">
                {{ __('How to Become a Member') }}
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-base text-[#617067] sm:text-lg">
                {{ __('Joining Maharaj Bachat is simple, transparent, and welcoming. Follow our streamlined 4-step process.') }}
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Step 1 --}}
            <div class="relative flex flex-col justify-between rounded-3xl border border-[#DCE9DF] bg-white p-7 shadow-sm transition hover:shadow-md">
                <div>
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#08713D] text-lg font-bold text-[#FFD91A] shadow-inner">
                        01
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-[#182A22]">{{ __('Step 1: Visit Our Cooperative') }}</h3>
                    <p class="mt-2 text-xs leading-relaxed text-[#617067]">
                        {{ __('Visit our head office at Kawasoti-2 BP Chowk, Nawalpur, or any branch counter to consult our friendly member desk.') }}
                    </p>
                </div>
                <div class="mt-6 border-t border-[#DCE9DF] pt-3 text-[11px] font-semibold text-[#08713D]">
                    📍 {{ __('Local Service Counters') }}
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="relative flex flex-col justify-between rounded-3xl border border-[#DCE9DF] bg-white p-7 shadow-sm transition hover:shadow-md">
                <div>
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#08713D] text-lg font-bold text-[#FFD91A] shadow-inner">
                        02
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-[#182A22]">{{ __('Step 2: Complete the Application') }}</h3>
                    <p class="mt-2 text-xs leading-relaxed text-[#617067]">
                        {{ __('Fill out the official membership form with your personal details, nominee nomination, and share subscription choices.') }}
                    </p>
                </div>
                <div class="mt-6 border-t border-[#DCE9DF] pt-3 text-[11px] font-semibold text-[#08713D]">
                    ✍️ {{ __('Online or Counter Form') }}
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="relative flex flex-col justify-between rounded-3xl border border-[#DCE9DF] bg-white p-7 shadow-sm transition hover:shadow-md">
                <div>
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#08713D] text-lg font-bold text-[#FFD91A] shadow-inner">
                        03
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-[#182A22]">{{ __('Step 3: Submit Required Documents') }}</h3>
                    <p class="mt-2 text-xs leading-relaxed text-[#617067]">
                        {{ __('Provide copies of your Nepali citizenship, two passport-sized photos, and verified nominee documentation.') }}
                    </p>
                </div>
                <div class="mt-6 border-t border-[#DCE9DF] pt-3 text-[11px] font-semibold text-[#08713D]">
                    📄 {{ __('Standard KYC Verification') }}
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="relative flex flex-col justify-between rounded-3xl border border-[#DCE9DF] bg-white p-7 shadow-sm transition hover:shadow-md">
                <div>
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#08713D] text-lg font-bold text-[#FFD91A] shadow-inner">
                        04
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-[#182A22]">{{ __('Step 4: Receive Your Membership') }}</h3>
                    <p class="mt-2 text-xs leading-relaxed text-[#617067]">
                        {{ __('Upon Board approval, receive your official Member Passbook, Member ID, and unlock full savings & credit facilities.') }}
                    </p>
                </div>
                <div class="mt-6 border-t border-[#DCE9DF] pt-3 text-[11px] font-semibold text-[#08713D]">
                    🎉 {{ __('Welcome to the Family') }}
                </div>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-full bg-[#08713D] px-8 py-3.5 text-sm font-bold text-white shadow-lg transition hover:bg-[#04552F] hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[#FFD91A]">
                <span>{{ __('Begin Membership Application Now') }}</span>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- ============================================================
     8. FREQUENTLY ASKED QUESTIONS (Accordion Section)
============================================================ --}}
<section id="faqs" class="fade-in py-20 bg-white">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="mb-14 text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#DCE9DF] bg-[#EFF8F1] px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] text-[#08713D]">
                {{ __('Answers & Guidance') }}
            </span>
            <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-[#04552F] sm:text-4xl">
                {{ __('Frequently Asked Questions') }}
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-base text-[#617067]">
                {{ __('Quick answers to common questions about accounts, credit eligibility, membership requirements, and policies.') }}
            </p>
        </div>

        <div class="space-y-4">
            @forelse($faqs as $faq)
                <details class="group rounded-2xl border border-[#DCE9DF] bg-[#EFF8F1]/40 p-5 transition-all duration-200 hover:border-[#08713D]/40 open:bg-white open:shadow-md">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-bold text-[#182A22] sm:text-base">
                        <span class="flex items-center gap-3">
                            <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-[#EFF8F1] text-xs font-bold text-[#08713D] group-open:bg-[#08713D] group-open:text-white transition">
                                ?
                            </span>
                            <span>{{ $faq->question() }}</span>
                        </span>
                        <svg class="h-5 w-5 shrink-0 text-[#08713D] transition group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="mt-4 border-t border-[#DCE9DF] pt-4 text-sm leading-relaxed text-[#617067]">
                        <p>{{ $faq->answer() }}</p>
                    </div>
                </details>
            @empty
                <div class="rounded-2xl border border-dashed border-[#DCE9DF] p-8 text-center text-sm text-[#617067]">
                    {{ __('No FAQs available at the moment.') }}
                </div>
            @endforelse
        </div>

        <div class="mt-10 rounded-2xl border border-[#DCE9DF] bg-[#EFF8F1] p-6 text-center text-sm text-[#182A22]">
            <p class="font-semibold">{{ __('Have a specific question not listed here?') }}</p>
            <p class="mt-1 text-xs text-[#617067]">{{ __('Our member service officers are always available during office hours.') }}</p>
            <div class="mt-4 flex flex-wrap items-center justify-center gap-3">
                <a href="tel:078540905" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-bold text-[#04552F] shadow-sm hover:bg-[#FFD91A]">
                    📞 078-540905
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-full bg-[#08713D] px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-[#04552F]">
                    ✉️ {{ __('Contact Us') }}
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     9. CALL TO ACTION SECTION (Institutional Green Theme)
============================================================ --}}
<section class="fade-in bg-[#04552F] py-16 text-white sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-white/10 bg-gradient-to-r from-[#033B22] via-[#04552F] to-[#08713D] p-8 sm:p-12 lg:p-16 shadow-2xl">
            <div class="grid items-center gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-1 text-xs font-bold uppercase tracking-[0.2em] text-[#FFD91A] backdrop-blur-sm">
                        {{ __('Join Maharaj Bachat') }}
                    </span>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                        {{ __('Let\'s Build Your Financial Future Together.') }}
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-emerald-100 sm:text-lg">
                        {{ __('Join our cooperative and explore financial services designed to support your goals. Safe savings, transparent credit, and mutual prosperity for every member.') }}
                    </p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col lg:items-end">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#FFD91A] px-7 py-3.5 text-sm font-bold text-[#04552F] shadow-lg transition hover:bg-[#ffe14d] focus:outline-none focus:ring-4 focus:ring-white">
                        <span>{{ __('Become a Member') }}</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-white/30 bg-white/10 px-7 py-3.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white hover:text-[#04552F]">
                        <span>{{ __('Contact Us') }}</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

