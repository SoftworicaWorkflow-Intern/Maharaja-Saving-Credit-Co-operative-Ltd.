@extends('layouts.app')

@section('title', $service->title() . ' · ' . __('Services') . ' · ' . __('Maharaj Bachat'))

@section('content')
@php
    $locale = app()->getLocale();
    $categoryLabel = match($service->category) {
        'savings' => __('Savings Services'),
        'loan' => __('Loan Services'),
        'member_support' => __('Member Support Services'),
        default => __('Cooperative Service')
    };
@endphp

<div class="bg-white">
    {{-- Header Banner --}}
    <section class="relative overflow-hidden bg-[#04552F] py-14 text-white sm:py-20">
        <div class="absolute inset-0">
            <img src="{{ asset('images/cooperative-service.png') }}" alt="{{ $service->title() }}" class="h-full w-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-r from-[#033B22]/95 via-[#04552F]/90 to-[#08713D]/80"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav aria-label="{{ __('Breadcrumb') }}" class="mb-5">
                <ol class="inline-flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-200/90 sm:text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="transition hover:text-[#FFD91A]">{{ __('Home') }}</a>
                    </li>
                    <li aria-hidden="true" class="text-emerald-400">/</li>
                    <li>
                        <a href="{{ route('services') }}" class="transition hover:text-[#FFD91A]">{{ __('Services') }}</a>
                    </li>
                    <li aria-hidden="true" class="text-emerald-400">/</li>
                    <li aria-current="page" class="text-[#FFD91A] font-bold">
                        {{ $service->title() }}
                    </li>
                </ol>
            </nav>

            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-1 text-xs font-bold uppercase tracking-[0.2em] text-[#FFD91A] backdrop-blur-sm">
                    {{ $categoryLabel }}
                </span>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                    {{ $service->title() }}
                </h1>
                <p class="mt-4 text-base leading-relaxed text-emerald-100 sm:text-lg">
                    {{ $service->shortDescription() }}
                </p>
            </div>
        </div>
    </section>

    {{-- Main Detail Body & Sidebar Layout --}}
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-3">
            {{-- Main Content Column --}}
            <div class="lg:col-span-2 space-y-12">
                {{-- Full Description Section --}}
                <section>
                    <h2 class="text-2xl font-bold text-[#04552F] sm:text-3xl">
                        {{ __('Overview & Service Details') }}
                    </h2>
                    <div class="mt-4 prose prose-emerald max-w-none text-base leading-relaxed text-[#344054]">
                        <p>{{ $service->fullDescription() }}</p>
                    </div>
                </section>

                {{-- Purpose Section (if applicable) --}}
                @if($service->purpose())
                    <section class="rounded-3xl border border-[#DCE9DF] bg-[#EFF8F1]/50 p-6 sm:p-8">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#08713D] text-lg text-white">🎯</span>
                            <h3 class="text-lg font-bold text-[#04552F]">{{ __('Purpose & Objective') }}</h3>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-[#344054]">
                            {{ $service->purpose() }}
                        </p>
                    </section>
                @endif

                {{-- Key Benefits Section --}}
                @if(!empty($service->benefits()))
                    <section>
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EFF8F1] text-lg text-[#08713D]">✨</span>
                            <h3 class="text-2xl font-bold text-[#04552F]">{{ __('Key Features & Member Benefits') }}</h3>
                        </div>
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach($service->benefits() as $benefit)
                                <div class="flex items-start gap-3 rounded-2xl border border-[#DCE9DF] bg-white p-4 shadow-sm">
                                    <svg class="h-5 w-5 shrink-0 text-[#08713D] mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-xs font-semibold text-[#182A22] sm:text-sm">{{ $benefit }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Eligibility Section --}}
                @if($service->eligibility())
                    <section class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EFF8F1] text-lg text-[#08713D]">📋</span>
                            <h3 class="text-xl font-bold text-[#04552F]">{{ __('Eligibility Requirements') }}</h3>
                        </div>
                        <p class="mt-4 text-sm leading-relaxed text-[#344054]">
                            {{ $service->eligibility() }}
                        </p>
                    </section>
                @endif

                {{-- Required Documents Section --}}
                @if(!empty($service->requiredDocuments()))
                    <section class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-8">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#EFF8F1] text-lg text-[#08713D]">📄</span>
                            <h3 class="text-xl font-bold text-[#04552F]">{{ __('Required Documents') }}</h3>
                        </div>
                        <ul class="mt-5 space-y-3">
                            @foreach($service->requiredDocuments() as $doc)
                                <li class="flex items-start gap-3 text-sm text-[#344054]">
                                    <span class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#EFF8F1] text-xs font-bold text-[#08713D] mt-0.5">✓</span>
                                    <span>{{ $doc }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                {{-- Application Process Section --}}
                @if($service->process())
                    <section class="rounded-3xl border border-[#DCE9DF] bg-[#EFF8F1] p-6 sm:p-8">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#08713D] text-lg text-white">🔄</span>
                            <h3 class="text-xl font-bold text-[#04552F]">{{ __('How to Apply & Next Steps') }}</h3>
                        </div>
                        <p class="mt-4 text-sm leading-relaxed text-[#344054]">
                            {{ $service->process() }}
                        </p>
                    </section>
                @endif
            </div>

            {{-- Sidebar Column --}}
            <aside class="space-y-8">
                {{-- Action / Inquiry Card --}}
                <div class="rounded-3xl border border-[#DCE9DF] bg-[#EFF8F1] p-6 shadow-sm sm:p-8">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#08713D]">{{ __('Action Desk') }}</span>
                    <h3 class="mt-2 text-xl font-bold text-[#04552F]">{{ __('Ready to Apply or Inquire?') }}</h3>
                    <p class="mt-2 text-xs leading-relaxed text-[#617067]">
                        {{ __('Connect with our member desk or begin your membership application to access this scheme.') }}
                    </p>

                    <div class="mt-6 flex flex-col gap-3">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#08713D] px-5 py-3 text-sm font-bold text-white shadow-md transition hover:bg-[#04552F]">
                            <span>{{ __('Become a Member') }}</span>
                            <span aria-hidden="true">→</span>
                        </a>
                        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#08713D] bg-white px-5 py-3 text-sm font-semibold text-[#08713D] shadow-sm transition hover:bg-[#08713D] hover:text-white">
                            <span>{{ __('Contact Branch') }}</span>
                        </a>
                    </div>
                </div>

                {{-- Contact Information Box --}}
                <div class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-[#04552F]">{{ __('Branch Customer Desk') }}</h4>
                    <ul class="mt-4 space-y-3.5 text-xs text-[#344054]">
                        <li class="flex items-center gap-3">
                            <span class="text-base">📞</span>
                            <div>
                                <div class="text-[#617067]">{{ __('Phone') }}</div>
                                <a href="tel:078540905" class="font-bold text-[#08713D] hover:underline">078-540905 / 078-540530</a>
                            </div>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-base">✉️</span>
                            <div>
                                <div class="text-[#617067]">{{ __('Email') }}</div>
                                <a href="mailto:saccosmaharaja@gmail.com" class="font-bold text-[#08713D] hover:underline">saccosmaharaja@gmail.com</a>
                            </div>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-base">📍</span>
                            <div>
                                <div class="text-[#617067]">{{ __('Head Office') }}</div>
                                <span class="font-semibold text-[#182A22]">Kawasoti -2 BP Chowk, Nawalpur</span>
                            </div>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-base">⏰</span>
                            <div>
                                <div class="text-[#617067]">{{ __('Office Hours') }}</div>
                                <span class="font-semibold text-[#182A22]">10:00 AM – 5:00 PM (Sun–Fri)</span>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Related Services in this Category --}}
                @if($relatedServices->isNotEmpty())
                    <div class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-[#04552F]">
                            {{ __('Other :category', ['category' => $categoryLabel]) }}
                        </h4>
                        <div class="mt-4 divide-y divide-[#DCE9DF]">
                            @foreach($relatedServices as $related)
                                <a href="{{ route('services.show', $related) }}" class="group block py-3 first:pt-0 last:pb-0 transition">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-[#182A22] group-hover:text-[#08713D] group-hover:underline">
                                            {{ $related->title() }}
                                        </span>
                                        <span class="text-xs text-[#08713D] transition group-hover:translate-x-1">→</span>
                                    </div>
                                    <p class="mt-1 text-xs text-[#617067] line-clamp-2">
                                        {{ $related->shortDescription() }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>
@endsection

