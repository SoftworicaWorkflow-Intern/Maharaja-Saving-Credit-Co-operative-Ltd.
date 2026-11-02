@extends('layouts.app')

@section('title', __('Maharaj Bachat | Secure Savings. A Stronger Future.'))

@section('content')
@php
    $heroHeadlines = app()->getLocale() === 'ne'
        ? [
            ['before' => 'सुरक्षित बचत,', 'highlight' => 'उज्ज्वल भविष्य।'],
            ['before' => 'तपाईंको विश्वास,', 'highlight' => 'हाम्रो प्रतिबद्धता।'],
            ['before' => 'सँगै अघि बढौँ,', 'highlight' => 'समृद्धि निर्माण गरौँ।'],
            ['before' => 'सहकारी वित्तमार्फत', 'highlight' => 'समुदायको सशक्तीकरण।'],
        ]
        : [
            ['before' => 'Secure Savings,', 'highlight' => 'Brighter Futures.'],
            ['before' => 'Your Trust,', 'highlight' => 'Our Commitment.'],
            ['before' => 'Growing Together,', 'highlight' => 'Building Prosperity.'],
            ['before' => 'Empowering Communities Through', 'highlight' => 'Cooperative Finance.'],
        ];
@endphp
<div class="bg-white">
    @if ($tickerNotices->isNotEmpty())
        @php
            $locale = app()->getLocale();
            $tickerTitle = fn ($notice) => $locale === 'ne'
                ? ($notice->title_np ?: $notice->title_en)
                : ($notice->title_en ?: $notice->title_np);
        @endphp
        <section class="fade-in px-4 pt-6 sm:px-6 lg:px-8" aria-label="{{ $locale === 'ne' ? 'ताजा समाचार' : 'Latest news' }}">
            <div data-news-ticker lang="{{ $locale }}" class="news-ticker mx-auto flex max-w-7xl overflow-hidden rounded-xl border border-[#08713D]/20 bg-[#EFF8F1] shadow-sm">
                <div class="z-10 flex shrink-0 items-center gap-2 bg-[#08713D] px-3 py-3 text-xs font-bold tracking-wide text-white sm:px-4 sm:text-sm">
                    <svg aria-hidden="true" class="h-4 w-4 shrink-0 text-[#FFD91A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10v4h4l5 4V6l-5 4H3Zm13-1a4 4 0 0 1 0 6m2-9a8 8 0 0 1 0 12"/>
                    </svg>
                    <span lang="{{ $locale }}">{{ $locale === 'ne' ? 'ताजा समाचार' : 'LATEST NEWS' }}</span>
                </div>
                <div class="min-w-0 flex-1 overflow-hidden">
                    <div class="news-ticker-track flex w-max items-center" aria-label="{{ $locale === 'ne' ? 'प्रकाशित सूचना' : 'Published notices' }}">
                        @foreach ([false, true] as $isDuplicate)
                            <div @if ($isDuplicate) aria-hidden="true" @endif class="news-ticker-sequence flex shrink-0 items-center gap-8 whitespace-nowrap px-5 py-3">
                                @foreach ($tickerNotices as $notice)
                                    <a href="{{ route('notices.show', $notice) }}" lang="{{ $locale }}" @if ($isDuplicate) tabindex="-1" @endif class="inline-flex items-center gap-3 text-sm font-semibold text-[#04552F] transition-colors hover:text-[#08713D] hover:underline focus:outline-none focus:ring-2 focus:ring-[#08713D]">
                                        <span aria-hidden="true" class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#FFD91A] ring-1 ring-[#08713D]/30"></span>
                                        <span>{{ $tickerTitle($notice) }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="news-ticker-static flex flex-wrap items-center gap-x-8 gap-y-2 px-4 py-3 text-sm font-semibold text-[#04552F]">
                        @foreach ($tickerNotices as $notice)
                            <a href="{{ route('notices.show', $notice) }}" lang="{{ $locale }}" class="hover:text-[#08713D] hover:underline focus:outline-none focus:ring-2 focus:ring-[#08713D]">{{ $tickerTitle($notice) }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="fade-in px-4 sm:px-6 lg:px-8 {{ $tickerNotices->isNotEmpty() ? 'pb-10 pt-0 lg:pb-14' : 'py-10 lg:py-14' }}">
        <div data-hero-carousel class="relative mx-auto flex min-h-[540px] max-w-7xl items-center overflow-hidden rounded-xl bg-[#04552F] shadow-[0_25px_50px_rgba(8,113,61,0.16)] sm:min-h-[600px]" aria-roledescription="carousel" aria-label="{{ __('Maharaj Bachat cooperative') }}">
            <div class="absolute inset-0">
                <img data-hero-slide src="{{ asset('images/cooperative-service.png') }}" alt="{{ __('A cooperative staff member serving an elderly Nepali member') }}" class="absolute inset-0 h-full w-full scale-100 object-cover opacity-100 transition-[opacity,transform] duration-[1300ms] ease-out motion-reduce:transition-none" aria-hidden="false">
                <img data-hero-slide src="{{ asset('images/savings-growth.png') }}" alt="{{ __('Growing plants and coins representing savings and financial growth') }}" class="absolute inset-0 h-full w-full scale-105 object-cover opacity-0 transition-[opacity,transform] duration-[1300ms] ease-out motion-reduce:transition-none" aria-hidden="true" hidden>
                <img data-hero-slide src="{{ asset('images/nepali-cooperative-community.svg') }}" alt="{{ __('Illustration of Nepali cooperative members meeting together') }}" class="absolute inset-0 h-full w-full scale-105 object-cover opacity-0 transition-[opacity,transform] duration-[1300ms] ease-out motion-reduce:transition-none" aria-hidden="true" hidden>
                <div class="absolute inset-0 bg-gradient-to-r from-[#033B22]/90 via-[#04552F]/65 to-[#04552F]/20"></div>
            </div>

            <div class="relative z-10 max-w-3xl px-8 py-20 text-white sm:px-12 lg:px-16">
                <span class="mb-4 inline-flex w-fit rounded-full border border-white/30 bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white backdrop-blur-sm">शक्ति र सेवा</span>
                <h1 data-rotating-headline class="grid text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl" aria-live="polite" aria-atomic="true">
                    @foreach ($heroHeadlines as $index => $headline)
                        <span data-headline-slide class="col-start-1 row-start-1 block transform-gpu transition-[opacity,transform] duration-[350ms] ease-out motion-reduce:transition-none {{ $index === 0 ? 'translate-y-0 opacity-100' : 'translate-y-3 opacity-0' }}" aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                            {{ $headline['before'] }}
                            <span class="text-[#FFD91A] underline decoration-[#08713D] decoration-4 underline-offset-4 [text-shadow:0_2px_0_#08713D]">{{ $headline['highlight'] }}</span>
                        </span>
                    @endforeach
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-white/90">{{ __('We support responsible savings, accessible credit, and community-focused financial services designed to strengthen every member\'s future.') }}</p>
            </div>

            <div class="absolute bottom-6 right-6 z-10 flex items-center gap-2 sm:bottom-8 sm:right-8" aria-label="{{ __('Choose an image') }}">
                <button type="button" data-hero-indicator aria-label="{{ __('Show image :number', ['number' => 1]) }}" aria-current="true" class="h-2.5 w-7 rounded-full bg-[#FFD91A] transition-all"></button>
                <button type="button" data-hero-indicator aria-label="{{ __('Show image :number', ['number' => 2]) }}" aria-current="false" class="h-2.5 w-2.5 rounded-full bg-white/70 transition-all"></button>
                <button type="button" data-hero-indicator aria-label="{{ __('Show image :number', ['number' => 3]) }}" aria-current="false" class="h-2.5 w-2.5 rounded-full bg-white/70 transition-all"></button>
            </div>
        </div>
    </section>

    {{-- OUR IMPACT section --}}
    <section id="our-impact" class="fade-in bg-[#08713D] py-16 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] text-[#FFD91A] backdrop-blur-sm">
                    {{ __('Our Impact') }}
                </span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    {{ __('Growing Together With Our Members') }}
                </h2>
                <p class="mx-auto mt-3 max-w-2xl text-base text-emerald-100">
                    {{ __('A trusted cooperative building stronger financial futures through savings, credit, and community service.') }}
                </p>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="text-sm font-medium text-[#617067]">{{ __('Total Members') }}</div>
                    <div class="mt-3 text-3xl font-extrabold text-[#08713D] sm:text-4xl">
                        @if ($activeMemberCount !== null)
                            <span data-active-member-count="{{ $activeMemberCount }}" data-counter data-target="{{ $activeMemberCount }}" data-prefix="" data-suffix="" data-format="comma">{{ number_format($activeMemberCount) }}</span>
                        @else
                            <span>{{ __('Not available') }}</span>
                        @endif
                    </div>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="text-sm font-medium text-[#617067]">{{ __('Total Savings') }}</div>
                    <div class="mt-3 text-3xl font-extrabold text-[#08713D] sm:text-4xl">
                        <span data-counter data-target="85" data-prefix="Rs. " data-suffix=" Cr+" data-format="int">Rs. 85 Cr+</span>
                    </div>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="text-sm font-medium text-[#617067]">{{ __('Total Loans') }}</div>
                    <div class="mt-3 text-3xl font-extrabold text-[#08713D] sm:text-4xl">
                        <span data-counter data-target="62" data-prefix="Rs. " data-suffix=" Cr+" data-format="int">Rs. 62 Cr+</span>
                    </div>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-sm transition hover:shadow-md">
                    <div class="text-sm font-medium text-[#617067]">{{ __('Total Branches') }}</div>
                    <div class="mt-3 text-3xl font-extrabold text-[#08713D] sm:text-4xl">
                        <span data-counter data-target="12" data-prefix="" data-suffix="+" data-format="int">12+</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="member-directory" class="fade-in bg-[#F5F8F5] px-4 py-10 sm:px-6 sm:py-14 lg:px-8" aria-labelledby="member-directory-heading">
        <div class="mx-auto max-w-5xl overflow-hidden rounded-2xl border border-[#DCE9DF] bg-white shadow-lg shadow-[#14532D]/10">
            <div class="flex items-center gap-3 bg-gradient-to-r from-[#14532D] to-[#27804A] px-4 py-4 text-white sm:px-6">
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/15 text-[#FACC15]" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="4" width="18" height="16" rx="2"/>
                        <circle cx="9" cy="10" r="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 16c.6-1.3 1.6-2 3-2s2.4.7 3 2m3-6h3m-3 4h3"/>
                    </svg>
                </span>
                <div>
                    <h2 id="member-directory-heading" class="text-base font-extrabold">{{ __('Member Directory') }}</h2>
                    <p class="mt-0.5 text-xs text-white/80">{{ __('Showing registered members of Maharaj Bachat tatha Rin Sahakari Sanstha Ltd.') }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E4E9EF] bg-[#F8FAFC] px-4 py-3 text-xs text-[#617067] sm:px-6">
                <p>
                    {{ __('Showing :shown of :total members', [
                        'shown' => $directoryMembers->count(),
                        'total' => $activeMemberCount !== null ? number_format($activeMemberCount) : '—',
                    ]) }}
                </p>
                <a href="{{ route('members.index') }}" class="inline-flex min-h-9 items-center justify-center gap-2 rounded-md bg-[#27804A] px-3 py-2 font-bold text-white transition hover:bg-[#14532D] focus:outline-none focus:ring-2 focus:ring-[#FACC15] focus:ring-offset-2">
                    <svg aria-hidden="true" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m16-13v6m3-3h-6"/>
                        <circle cx="9" cy="7" r="4"/>
                    </svg>
                    {{ __('View All Members') }}
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[38rem] border-collapse text-left text-xs">
                    <thead class="bg-white text-[10px] font-bold uppercase tracking-wide text-[#344054]">
                        <tr class="border-b border-[#E4E9EF]">
                            <th scope="col" class="w-14 px-3 py-3 sm:px-4">{{ __('S.N') }}</th>
                            <th scope="col" class="px-3 py-3 sm:px-4">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg aria-hidden="true" class="h-3 w-3 text-[#27804A]" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 9a7 7 0 1 1 14 0H5Z"/></svg>
                                    {{ __('Name') }}
                                </span>
                            </th>
                            <th scope="col" class="px-3 py-3 sm:px-4">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg aria-hidden="true" class="h-3 w-3 text-[#27804A]" viewBox="0 0 24 24" fill="currentColor"><path d="M4 5h16v14H4zM7 8v2h2V8zm4 0v2h6V8zm-4 4v2h2v-2zm4 0v2h6v-2z"/></svg>
                                    {{ __('Code') }}
                                </span>
                            </th>
                            <th scope="col" class="px-3 py-3 sm:px-4">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg aria-hidden="true" class="h-3 w-3 text-[#27804A]" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7h18v10H3zM6 10h4v4H6z"/></svg>
                                    {{ __('Share Number') }}
                                </span>
                            </th>
                            <th scope="col" class="w-16 px-3 py-3 text-center sm:px-4">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E4E9EF]">
                        @forelse ($directoryMembers as $index => $directoryMember)
                            <tr data-member-preview-row class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-[#F8FAFC]' }} transition-colors hover:bg-[#F0FBF3]">
                                <td class="px-3 py-2.5 sm:px-4">
                                    <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-md bg-[#F1F5F9] px-1.5 font-bold text-[#617067]">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-3 py-2.5 sm:px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-md border border-[#B9DCC4] bg-[#EFF8F1] text-[10px] font-bold text-[#14532D]" aria-hidden="true">MB</span>
                                        <span class="font-bold text-[#182A22]">{{ $directoryMember->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-2.5 sm:px-4">
                                    <span data-member-number-value class="inline-flex rounded-full border border-[#B9DCC4] bg-[#F0FBF3] px-2 py-1 font-mono text-[10px] text-[#27804A]" aria-label="{{ __('Hidden for privacy') }}">••••••••</span>
                                </td>
                                <td class="px-3 py-2.5 sm:px-4">
                                    <span class="inline-flex rounded-full border border-[#F3E28C] bg-[#FFF9D7] px-2 py-1 font-mono text-[10px] text-[#9A7600]" aria-label="{{ __('Hidden for privacy') }}">••••</span>
                                </td>
                                <td class="px-3 py-2.5 text-center sm:px-4">
                                    <button type="button" data-member-number-toggle data-member-number-url="{{ route('members.number', $directoryMember->id) }}" data-hidden-label="{{ __('Hidden for privacy') }}" data-show-label="{{ __('Show member number') }}" data-hide-label="{{ __('Hide member number') }}" data-unavailable-label="{{ __('Not available') }}" aria-label="{{ __('Show member number') }}" aria-pressed="false" class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-[#B9DCC4] bg-[#F0FBF3] text-[#14532D] transition hover:bg-[#DDF3E4] focus:outline-none focus:ring-2 focus:ring-[#27804A] focus:ring-offset-1">
                                        <svg data-member-eye-closed aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.7 10.7 0 0 1 12 5c6.5 0 10 7 10 7a15.7 15.7 0 0 1-3.1 3.8M6.2 6.2C3.5 8 2 12 2 12s3.5 7 10 7a10.7 10.7 0 0 0 3-.4"/>
                                        </svg>
                                        <svg data-member-eye-open aria-hidden="true" class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/>
                                            <circle cx="12" cy="12" r="2.5"/>
                                        </svg>
                                    </button>
                                    <span data-member-number-error role="alert" class="hidden text-[10px] text-red-700">{{ __('Unable to load member number. Please try again.') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-sm text-[#617067]">{{ __('No registered members to preview.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-2 border-t border-[#E4E9EF] bg-[#F8FAFC] px-4 py-3 text-[11px] text-[#617067] sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <p class="inline-flex items-center gap-2">
                    <svg aria-hidden="true" class="h-3.5 w-3.5 shrink-0 text-[#27804A]" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><path d="M11 10h2v7h-2zm0-4h2v2h-2z" fill="white"/></svg>
                    {{ __('Previewing :count registered members.', ['count' => $directoryMembers->count()]) }}
                    <span>{{ __('Use View All Members for the complete directory.') }}</span>
                </p>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1 font-bold text-[#14532D] hover:underline">
                    <svg aria-hidden="true" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5m5 5H3"/>
                    </svg>
                    {{ __('Member Login for Full Access') }}
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================
         Financial Services Section — What We Offer (Light Gray Background)
    ============================================================ --}}
    <section id="what-we-offer" class="overflow-hidden border-y border-[#DCE9DF] bg-[#EFF8F1] py-16 sm:py-20" aria-labelledby="what-we-offer-heading">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center fade-in">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#DCE9DF] bg-white px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] text-[#08713D] shadow-sm">
                    {{ __('What we offer') }}
                </span>
                <h2 id="what-we-offer-heading" class="mt-4 text-3xl font-extrabold tracking-tight text-[#04552F] sm:text-4xl lg:text-5xl">
                    {{ __('Financial services built around members') }}
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-base text-[#617067] sm:text-lg">
                    {{ __('Explore our member-first savings plans, flexible credit schemes, and official cooperative updates.') }}
                </p>
                <p class="mt-2 text-xs font-semibold uppercase tracking-wider text-[#08713D]">
                    {{ __('Click a service card below to view full details and options') }}
                </p>
            </div>

            {{-- 3 Service Cards — each navigates to its own dedicated page --}}
            <div class="grid gap-6 md:grid-cols-3">

                {{-- Savings Products Card --}}
                <div class="service-card-slide-right flex flex-col" style="--stagger-delay: 0ms;">
                    <a href="{{ route('services') }}#savings"
                        class="group relative flex flex-1 flex-col justify-between rounded-3xl p-6 text-left transition-all duration-300 sm:p-8 bg-white border border-[#DCE9DF] text-[#182A22] shadow-sm hover:shadow-xl hover:border-[#08713D]/40 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-[#FFD91A]">
                        <div>
                            <div class="mb-5 flex items-center justify-between">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#EFF8F1] text-2xl text-[#08713D] shadow-inner transition group-hover:scale-110">💰</span>
                                <span class="rounded-full bg-[#EFF8F1] px-3 py-1 text-xs font-bold text-[#08713D] uppercase tracking-wider group-hover:bg-[#08713D] group-hover:text-white transition">{{ __('Explore') }}</span>
                            </div>
                            <h3 class="text-xl font-bold tracking-tight text-[#182A22] sm:text-2xl group-hover:text-[#08713D] transition">{{ __('Savings Products') }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-[#617067]">
                                {{ __('Flexible savings schemes designed to help members build security and long-term financial confidence.') }}
                            </p>
                            <ul class="mt-4 space-y-1.5 text-xs text-[#617067]">
                                <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#08713D]"></span>{{ __('Regular & Monthly Deposit Schemes') }}</li>
                                <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#08713D]"></span>{{ __('Fixed Deposit up to 5 years') }}</li>
                                <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#08713D]"></span>{{ __("Children's & Senior Citizen Plans") }}</li>
                            </ul>
                        </div>
                        <div class="mt-6 flex items-center justify-between border-t border-[#DCE9DF] pt-4 text-xs font-bold uppercase tracking-wider text-[#08713D] group-hover:text-[#04552F]">
                            <span>{{ __('View All Savings Plans') }}</span>
                            <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </div>
                    </a>
                </div>

                {{-- Loan Support Card --}}
                <div class="service-card-slide-right flex flex-col" style="--stagger-delay: 150ms;">
                    <a href="{{ route('services') }}#loans"
                        class="group relative flex flex-1 flex-col justify-between rounded-3xl p-6 text-left transition-all duration-300 sm:p-8 bg-white border border-[#DCE9DF] text-[#182A22] shadow-sm hover:shadow-xl hover:border-[#08713D]/40 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-[#FFD91A]">
                        <div>
                            <div class="mb-5 flex items-center justify-between">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#EFF8F1] text-2xl text-[#08713D] shadow-inner transition group-hover:scale-110">🏦</span>
                                <span class="rounded-full bg-[#EFF8F1] px-3 py-1 text-xs font-bold text-[#08713D] uppercase tracking-wider group-hover:bg-[#08713D] group-hover:text-white transition">{{ __('Explore') }}</span>
                            </div>
                            <h3 class="text-xl font-bold tracking-tight text-[#182A22] sm:text-2xl group-hover:text-[#08713D] transition">{{ __('Loan Support') }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-[#617067]">
                                {{ __('Need-based credit options for personal, business, agricultural, and emergency requirements.') }}
                            </p>
                            <ul class="mt-4 space-y-1.5 text-xs text-[#617067]">
                                <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#08713D]"></span>{{ __('Personal & Business Loans') }}</li>
                                <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#08713D]"></span>{{ __('Agricultural & Education Loans') }}</li>
                                <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#08713D]"></span>{{ __('48-hour approval process') }}</li>
                            </ul>
                        </div>
                        <div class="mt-6 flex items-center justify-between border-t border-[#DCE9DF] pt-4 text-xs font-bold uppercase tracking-wider text-[#08713D] group-hover:text-[#04552F]">
                            <span>{{ __('View All Loan Schemes') }}</span>
                            <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </div>
                    </a>
                </div>

                {{-- Notices & Updates Card --}}
                <div class="service-card-slide-right flex flex-col" style="--stagger-delay: 300ms;">
                    <a href="{{ route('notices') }}"
                        class="group relative flex flex-1 flex-col justify-between rounded-3xl p-6 text-left transition-all duration-300 sm:p-8 bg-white border border-[#DCE9DF] text-[#182A22] shadow-sm hover:shadow-xl hover:border-[#08713D]/40 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-[#FFD91A]">
                        <div>
                            <div class="mb-5 flex items-center justify-between">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#EFF8F1] text-2xl text-[#08713D] shadow-inner transition group-hover:scale-110">📣</span>
                                <span class="rounded-full bg-[#EFF8F1] px-3 py-1 text-xs font-bold text-[#08713D] uppercase tracking-wider group-hover:bg-[#08713D] group-hover:text-white transition">{{ __('Explore') }}</span>
                            </div>
                            <h3 class="text-xl font-bold tracking-tight text-[#182A22] sm:text-2xl group-hover:text-[#08713D] transition">{{ __('Notices & Updates') }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-[#617067]">
                                {{ __('Clear information on schemes, announcements, and member notices in one accessible place.') }}
                            </p>
                            <ul class="mt-4 space-y-1.5 text-xs text-[#617067]">
                                <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#08713D]"></span>{{ __('AGM & Official Notices') }}</li>
                                <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#08713D]"></span>{{ __('Dividend & Financial Reports') }}</li>
                                <li class="flex items-center gap-2"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#08713D]"></span>{{ __('Scheme & Holiday Updates') }}</li>
                            </ul>
                        </div>
                        <div class="mt-6 flex items-center justify-between border-t border-[#DCE9DF] pt-4 text-xs font-bold uppercase tracking-wider text-[#08713D] group-hover:text-[#04552F]">
                            <span>{{ __('Browse All Notices') }}</span>
                            <svg class="h-4 w-4 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================================
         Leadership Messages
    ============================================================ --}}
    <section id="leadership-messages" class="bg-white px-4 py-16 font-['Inter'] sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mx-auto mb-10 max-w-2xl text-center">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#08713D]">{{ __('LEADERSHIP MESSAGES') }}</p>
                <h2 class="mt-3 text-3xl font-bold text-[#04552F] sm:text-4xl">{{ __('Words from Our Leadership') }}</h2>
            </div>

            <div class="space-y-8">
                @foreach ($leadershipMessages as $leader => $message)
                    @php
                        $leaderName = $message['name'];
                        $portraitFirst = $leader === 'chairperson';
                    @endphp
                    <article class="fade-in grid min-w-0 overflow-hidden rounded-3xl border border-[#DCE9DF] bg-[#EFF8F1] shadow-sm lg:grid-cols-2">
                        <div data-leadership-portrait="{{ $portraitFirst ? 'left' : 'right' }}" class="{{ $portraitFirst ? 'order-1 lg:order-1 leadership-portrait-slide-left' : 'order-1 lg:order-2 leadership-portrait-slide-right' }} relative min-h-72 overflow-hidden bg-[#E3F1E7] sm:min-h-96 lg:min-h-full">
                            @if ($message['has_portrait'])
                                <img src="{{ route('leadership.portrait', $leader) }}" alt="{{ __('Portrait of :name', ['name' => $leaderName ?: __($message['designation'])]) }}" class="absolute inset-0 h-full w-full object-cover">
                            @elseif ($leader === 'chairperson')
                                <img src="{{ asset('images/chairperson-message.png') }}" alt="{{ __('Portrait of the Chairperson') }}" class="absolute inset-0 h-full w-full object-cover">
                            @elseif ($leader === 'secretary')
                                <img src="{{ asset('images/secretary-portrait.png') }}" alt="{{ __('Portrait of the Secretary') }}" class="absolute inset-0 h-full w-full object-cover">
                            @else
                                <div role="img" aria-label="{{ __('Portrait not provided for :designation', ['designation' => __($message['designation'])]) }}" class="absolute inset-0 flex items-center justify-center text-[#08713D]/35">
                                    <svg aria-hidden="true" class="h-32 w-32 sm:h-40 sm:w-40" viewBox="0 0 120 120" fill="none">
                                        <circle cx="60" cy="40" r="23" stroke="currentColor" stroke-width="5"/>
                                        <path d="M18 108c2-24 19-39 42-39s40 15 42 39" stroke="currentColor" stroke-linecap="round" stroke-width="5"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="{{ $portraitFirst ? 'order-2 lg:order-2' : 'order-2 lg:order-1' }} flex min-w-0 flex-col justify-center p-6 sm:p-10 lg:p-12">
                            <div aria-hidden="true" class="mb-5 inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#FFD91A] text-[#04552F] shadow-sm">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M7.2 11.1c1.8 0 3.1 1.3 3.1 3.1 0 1.9-1.4 3.3-3.3 3.3S3.5 16 3.5 13.3c0-4.4 2.4-7 6.1-8.2v2.5c-1.6.8-2.4 1.9-2.4 3.5Zm10 0c1.8 0 3.1 1.3 3.1 3.1 0 1.9-1.4 3.3-3.3 3.3S13.5 16 13.5 13.3c0-4.4 2.4-7 6.1-8.2v2.5c-1.6.8-2.4 1.9-2.4 3.5Z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#08713D]">{{ __($message['label']) }}</p>
                            <h3 class="mt-2 text-2xl font-bold leading-tight text-[#04552F] sm:text-3xl">{{ __($message['title']) }}</h3>
                            <p class="mt-3 text-sm font-semibold text-[#08713D]">{{ $leaderName ?: __($message['designation']) }}</p>
                            <p class="mt-4 line-clamp-4 text-sm leading-7 text-[#344054]">{{ __($message['paragraphs'][0]) }}</p>
                            <a href="{{ route('leadership.show', $leader) }}" class="mt-6 inline-flex w-fit items-center gap-2 rounded-full bg-[#08713D] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#04552F] focus:outline-none focus:ring-2 focus:ring-[#FFD91A] focus:ring-offset-2">
                                {{ __('Read Full Message') }}
                                <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>




    {{-- ============================================================
         Why Members Trust Us
    ============================================================ --}}
    <section class="fade-in bg-[#04552F] py-16 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#FFD91A]">{{ __('Why members trust us') }}</p>
                    <h2 class="mt-4 text-3xl font-bold">{{ __('Community-first cooperative values') }}</h2>
                    <p class="mt-4 text-green-100">{{ __('We combine member-oriented service, transparent guidance, and financially responsible operations to support local prosperity.') }}</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white/5 p-5 ring-1 ring-white/10">
                        <div class="text-2xl font-bold text-[#FFD91A]">{{ __('Trust') }}</div>
                        <p class="mt-2 text-sm text-green-100">{{ __('Transparent processes and member care.') }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-5 ring-1 ring-white/10">
                        <div class="text-2xl font-bold text-[#FFD91A]">{{ __('Service') }}</div>
                        <p class="mt-2 text-sm text-green-100">{{ __('Responsive support for every stage.') }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-5 ring-1 ring-white/10">
                        <div class="text-2xl font-bold text-[#FFD91A]">{{ __('Growth') }}</div>
                        <p class="mt-2 text-sm text-green-100">{{ __('Savings solutions that grow with you.') }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-5 ring-1 ring-white/10">
                        <div class="text-2xl font-bold text-[#FFD91A]">{{ __('Community') }}</div>
                        <p class="mt-2 text-sm text-green-100">{{ __('Shared progress and local empowerment.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="fade-in border-t border-[#DCE9DF] bg-[#EFF8F1] px-4 py-16 sm:px-6 lg:px-8" aria-labelledby="gallery-heading">
    <div class="mx-auto max-w-7xl">
        <!-- Section Header -->
        <div class="mx-auto mb-10 max-w-2xl text-center">
            <p class="inline-flex rounded-full border border-[#08713D]/30 bg-white px-4 py-2 text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">
                {{ __('Our Community') }}
            </p>
            <h2 id="gallery-heading" class="mt-3 text-3xl font-bold text-[#04552F] sm:text-4xl">
                {{ __('Gallery') }}
            </h2>
            <p class="mt-4 text-sm leading-7 text-[#617067] sm:text-base">
                {{ __('Click on any image to view it in full size.') }}
            </p>
        </div>

        <!-- Single Row Image Grid (Scrollable on mobile/tablet) -->
        <div class="flex overflow-x-auto gap-4 pb-4 snap-x snap-mandatory hide-scrollbar">
            @php
                $images = [
                    'gallery-1.jpg',
                    'gallery-2.jpg',
                    'gallery-3.jpg',
                    'gallery-4.jpg',
                    'gallery-5.jpg',
                    'gallery-6.jpg'
                ];
            @endphp

            @foreach ($images as $image)
                <!-- Each item takes up full width on mobile, half on tablet, and 1/3 on small desktop, but on large desktop we fit all 6 -->
                <div class="group relative shrink-0 cursor-pointer overflow-hidden rounded-2xl border border-[#DCE9DF] bg-white shadow-sm transition-all duration-300 hover:shadow-md snap-start w-[85vw] sm:w-[45vw] md:w-[30vw] lg:w-[calc(16.666%-13.33px)]" 
                     onclick="openLightbox('{{ asset('images/gallery/' . $image) }}')">
                    
                    <img src="{{ asset('images/gallery/' . $image) }}" 
                         alt="Gallery Image" 
                         class="h-48 w-full object-cover object-center transition-transform duration-500 group-hover:scale-105">
                    
                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Lightbox Modal (Hidden by default) -->
    <div id="lightbox" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/90 p-4 backdrop-blur-sm" onclick="closeLightbox(event)">
        <!-- Close Button -->
        <button class="absolute right-6 top-6 text-white hover:text-gray-300 focus:outline-none" onclick="closeLightbox(event)">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <!-- Full Image -->
        <img id="lightbox-img" src="" alt="Full size image" class="max-h-[90vh] max-w-full rounded-lg object-contain shadow-2xl">
    </div>
</section>

<!-- Custom CSS to hide the scrollbar but keep functionality -->
<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<!-- JavaScript for Lightbox functionality -->
<script>
    function openLightbox(imageSrc) {
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        
        lightboxImg.src = imageSrc;
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        
        // Prevent background scrolling
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox(event) {
        // Only close if clicking the background overlay or close button, not the image itself
        if (event.target.id === 'lightbox' || event.target.closest('button')) {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            
            // Restore background scrolling
            document.body.style.overflow = 'auto';
        }
    }

    // Close on Escape key press
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const lightbox = document.getElementById('lightbox');
            if (!lightbox.classList.contains('hidden')) {
                lightbox.classList.add('hidden');
                lightbox.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }
    });
</script>

    {{-- ============================================================
         Latest Updates — Notices & Announcements (up to 7 cards)
         Each card links to the full notice detail page.
    ============================================================ --}}
    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Latest updates') }}</p>
                <h2 class="mt-2 text-3xl font-bold text-[#182A22]">{{ __('Notices & announcements') }}</h2>
            </div>
            <a href="{{ route('notices') }}" class="shrink-0 text-sm font-semibold text-[#08713D] hover:underline">{{ __('View all notices') }}</a>
        </div>

        @php $locale = app()->getLocale(); @endphp

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($featuredNotices as $notice)
                @php
                    $nTitle = $locale === 'ne' ? ($notice->title_np ?: $notice->title_en) : ($notice->title_en ?: $notice->title_np);
                    $nBody  = $locale === 'ne' ? ($notice->body_np  ?: $notice->body_en)  : ($notice->body_en  ?: $notice->body_np);
                @endphp
                <article class="flex flex-col justify-between rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm transition hover:border-[#08713D]/40 hover:shadow-md">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="rounded-full bg-[#EFF8F1] px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-[#08713D]">{{ $notice->category }}</span>
                            <span class="text-xs text-[#617067]">{{ $notice->published_at?->format('M d, Y') }}</span>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold leading-snug text-[#182A22]">
                            <a lang="{{ $locale }}" href="{{ route('notices.show', $notice) }}" class="hover:text-[#08713D] hover:underline">
                                {{ $nTitle }}
                            </a>
                        </h3>
                        <p class="mt-3 text-sm leading-relaxed text-[#617067]">
                            {{ Str::limit(strip_tags($nBody), 120) }}
                        </p>
                    </div>
                    <div class="mt-5 border-t border-[#DCE9DF]/70 pt-4">
                        <a href="{{ route('notices.show', $notice) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#08713D] transition hover:text-[#04552F]">
                            <span>{{ __('Read Full Notice') }}</span>
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-[#DCE9DF] bg-white p-8 text-[#617067] sm:col-span-2 lg:col-span-3">
                    {{ __('No published notices available yet.') }}
                </div>
            @endforelse
        </div>
    </section>
    @if ($latestReports->isNotEmpty() || $latestAnnualReport || $verifiedStatistics->isNotEmpty())
        <section class="bg-[#F5F8F5] py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @if ($verifiedStatistics->isNotEmpty())
                    <div class="mb-10">
                        <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Verified institutional information') }}</p>
                        <h2 class="mt-2 text-2xl font-extrabold text-[#182A22]">{{ __('Institutional highlights') }}</h2>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            @foreach ($verifiedStatistics as $statistic)
                                <article class="rounded-2xl border border-[#DCE9DF] bg-white p-5 shadow-sm">
                                    <h3 class="text-sm font-semibold text-[#617067]">{{ $statistic->localizedLabel() }}</h3>
                                    <p class="mt-2 text-2xl font-extrabold text-[#14532D]">{{ number_format((float) $statistic->value, 2) }} <span class="text-base font-semibold">{{ $statistic->localizedUnit() }}</span></p>
                                    <p class="mt-2 text-xs text-[#617067]">{{ __('Fiscal year') }}: {{ $statistic->fiscalYear->localizedLabel() }}</p>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if ($latestAnnualReport)
                    <div class="mb-10">
                        <div class="mb-5 flex flex-wrap items-end justify-between gap-4">
                            <div>
                                <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Institutional archive') }}</p>
                                <h2 class="mt-2 text-3xl font-extrabold text-[#182A22]">{{ __('Latest Annual Report') }}</h2>
                            </div>
                            <a href="{{ route('annual-reports.index') }}" class="font-semibold text-[#14532D] underline underline-offset-4">{{ __('Previous annual reports') }}</a>
                        </div>
                        <div class="max-w-sm">
                            <x-annual-report-card :report="$latestAnnualReport" />
                        </div>
                    </div>
                @endif
                @if ($latestReports->isNotEmpty())
                    <div class="flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Transparency and progress') }}</p>
                            <h2 class="mt-2 text-3xl font-extrabold text-[#182A22]">{{ __('Latest reports and publications') }}</h2>
                        </div>
                        <a href="{{ route('reports.index') }}" class="rounded-xl bg-[#14532D] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#27804A]">{{ __('View all reports') }}</a>
                    </div>
                    <div class="mt-6 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                        @foreach ($latestReports as $report)
                            <x-report-card :report="$report" />
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    <section class="fade-in border-t border-[#E8EDF0] bg-white px-4 py-16 sm:px-6 sm:py-20 lg:px-8" aria-labelledby="member-testimonials-heading">
        <div class="mx-auto max-w-7xl">
            <div class="mx-auto mb-12 max-w-2xl text-center">
                <p class="inline-flex items-center gap-2 rounded-full border border-[#CFE8D5] bg-[#F2FBF4] px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-[#27804A]">
                    <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 4h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-7l-5 4v-4H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/>
                    </svg>
                    {{ __('Testimonials') }}
                </p>
                <h2 id="member-testimonials-heading" class="mt-4 text-3xl font-extrabold tracking-tight text-[#111827] sm:text-4xl lg:text-5xl">{{ __('What Our Members Say') }}</h2>
                <p class="mt-4 text-base leading-7 text-[#7A8494] sm:text-lg">{{ __('Member perspectives on their cooperative experience.') }}</p>
                <p class="mt-2 text-xs leading-5 text-[#7A8494]">{{ __('Illustrative testimonials; replace with verified member feedback before publishing.') }}</p>
                <div class="mx-auto mt-5 flex w-16 items-center justify-center gap-1.5" aria-hidden="true">
                    <span class="h-1 w-10 rounded-full bg-[#27804A]"></span>
                    <span class="h-1 w-3 rounded-full bg-[#FACC15]"></span>
                    <span class="h-1 w-1.5 rounded-full bg-[#27804A]"></span>
                </div>
            </div>

            @php
                $testimonials = [
                    ['quote' => 'The cooperative team makes saving simple, clear, and dependable for my family.', 'name' => 'Sita Gurung', 'role' => 'Cooperative member', 'image' => 'https://images.pexels.com/photos/3769021/pexels-photo-3769021.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
                    ['quote' => 'I received friendly guidance when I needed credit to expand my small business.', 'name' => 'Ramesh Thapa', 'role' => 'Cooperative member', 'image' => 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
                    ['quote' => 'The service feels personal because the staff understands and supports our community.', 'name' => 'Maya K.C.', 'role' => 'Cooperative member', 'image' => 'https://images.pexels.com/photos/3769021/pexels-photo-3769021.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
                    ['quote' => 'Staff take time to explain the options in a way that feels easy to understand.', 'name' => 'Suman Adhikari', 'role' => 'Cooperative member', 'image' => 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
                    ['quote' => 'I appreciate being welcomed with patience whenever I visit the cooperative.', 'name' => 'Binita Gurung', 'role' => 'Cooperative member', 'image' => 'https://images.pexels.com/photos/3769021/pexels-photo-3769021.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
                    ['quote' => 'The team listens carefully and helps me find the information I need.', 'name' => 'Prakash Thapa', 'role' => 'Cooperative member', 'image' => 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
                    ['quote' => 'It is reassuring to have a local place where members can ask for guidance.', 'name' => 'Anisha Karki', 'role' => 'Cooperative member', 'image' => 'https://images.pexels.com/photos/3769021/pexels-photo-3769021.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
                    ['quote' => 'The service is approachable, and the staff make each visit feel comfortable.', 'name' => 'Deepak Bhandari', 'role' => 'Cooperative member', 'image' => 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
                    ['quote' => 'I value the clear communication and respectful support from the team.', 'name' => 'Sabina Tamang', 'role' => 'Cooperative member', 'image' => 'https://images.pexels.com/photos/3769021/pexels-photo-3769021.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
                    ['quote' => 'Being part of a member-focused cooperative helps me feel connected to my community.', 'name' => 'Rajesh Shrestha', 'role' => 'Cooperative member', 'image' => 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
                ];
            @endphp

            <div data-testimonial-carousel role="region" aria-roledescription="{{ __('carousel') }}" aria-label="{{ __('What Our Members Say') }}" class="mx-auto max-w-7xl">
                <div data-testimonial-track class="flex snap-x snap-mandatory gap-0 overflow-x-auto scroll-smooth pb-2 sm:gap-5 md:gap-6" aria-live="polite" aria-atomic="false">
                    @foreach ($testimonials as $index => $testimonial)
                        <figure data-testimonial-slide role="group" aria-roledescription="{{ __('slide') }}" aria-label="{{ __(':current of :total', ['current' => $index + 1, 'total' => count($testimonials)]) }}" aria-hidden="false" class="relative flex min-h-[18rem] w-full shrink-0 snap-start flex-col rounded-[1.5rem] border border-[#E5EAF0] bg-[#F8FAFC] p-5 sm:w-[calc(50%-0.75rem)] sm:p-6 lg:w-[calc(33.333%-1rem)]">
                            <span aria-hidden="true" class="absolute left-5 top-3 font-serif text-6xl font-bold leading-none text-[#EAF4EC] sm:left-7 sm:top-4">“</span>
                            <div class="relative flex gap-1 text-[#F4B400]" aria-label="{{ __('5 stars') }}">
                                @for ($star = 0; $star < 5; $star++)
                                    <span aria-hidden="true">★</span>
                                @endfor
                            </div>
                            <blockquote class="mt-4 flex-1 text-sm leading-6 text-[#737D8D] sm:text-base">“{{ __($testimonial['quote']) }}”</blockquote>
                            <figcaption class="mt-5 flex items-center gap-3">
                                <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}" class="h-10 w-10 shrink-0 rounded-full border-2 border-[#EAF4EC] bg-white object-cover">
                                <div>
                                    <div class="font-bold text-[#18202D]">{{ $testimonial['name'] }}</div>
                                    <div class="mt-1 text-sm text-[#7A8494]">{{ __($testimonial['role']) }}</div>
                                </div>
                            </figcaption>
                        </figure>
                    @endforeach
                    <span aria-hidden="true" class="w-0 shrink-0 sm:w-[calc(50%-0.75rem)] lg:w-[calc(66.667%-0.5rem)]"></span>
                </div>

                <div class="mt-5 flex justify-center">
                    <div role="group" aria-label="{{ __('Testimonial pages') }}" class="flex items-center gap-2">
                        @foreach ($testimonials as $index => $testimonial)
                            <button type="button" data-testimonial-page="{{ $index }}" aria-label="{{ __('Go to testimonial :number', ['number' => $index + 1]) }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" class="h-2.5 w-2.5 rounded-full bg-[#DDE2E8] transition-all hover:bg-[#27804A] aria-[current=true]:w-7 aria-[current=true]:bg-[#27804A] focus:outline-none focus:ring-2 focus:ring-[#27804A] focus:ring-offset-2"></button>
                        @endforeach
                    </div>
                </div>
                <div class="mt-8 flex justify-center gap-3">
                    <button type="button" data-testimonial-previous aria-label="{{ __('Previous testimonial') }}" class="inline-flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#CFE8D5] bg-[#F2FBF4] text-[#27804A] transition hover:border-[#27804A] hover:bg-[#E6F6EA] focus:outline-none focus:ring-2 focus:ring-[#27804A] focus:ring-offset-2">
                        <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <button type="button" data-testimonial-next aria-label="{{ __('Next testimonial') }}" class="inline-flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#CFE8D5] bg-[#F2FBF4] text-[#27804A] transition hover:border-[#27804A] hover:bg-[#E6F6EA] focus:outline-none focus:ring-2 focus:ring-[#27804A] focus:ring-offset-2">
                        <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .service-card-slide-right {
        opacity: 0;
        transform: translateX(80px);
        transition: opacity 700ms cubic-bezier(0.16, 1, 0.3, 1), transform 700ms cubic-bezier(0.16, 1, 0.3, 1);
        transition-delay: var(--stagger-delay, 0ms);
        will-change: opacity, transform;
    }

    .service-card-slide-right.show {
        opacity: 1;
        transform: translateX(0);
    }

    @media (prefers-reduced-motion: reduce) {
        .service-card-slide-right {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
    }
</style>

<script>
    (function () {
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Hero Carousel auto-scroll
        document.querySelectorAll('[data-hero-carousel]').forEach((carousel) => {
            if (carousel.dataset.heroAutoplayInitialized === 'true') return;
            carousel.dataset.heroAutoplayInitialized = 'true';

            const slides = [...carousel.querySelectorAll('[data-hero-slide]')];
            const indicators = [...carousel.querySelectorAll('[data-hero-indicator]')];
            if (slides.length < 2) return;

            const autoInterval = 4500;
            let activeIndex = 0;
            let timer = null;

            const showSlide = (index) => {
                activeIndex = (index + slides.length) % slides.length;

                slides.forEach((slide, slideIndex) => {
                    const isActive = slideIndex === activeIndex;
                    slide.classList.toggle('opacity-0', !isActive);
                    slide.hidden = !isActive;
                    slide.setAttribute('aria-hidden', String(!isActive));
                });

                indicators.forEach((indicator, indicatorIndex) => {
                    const isActive = indicatorIndex === activeIndex;
                    indicator.setAttribute('aria-current', String(isActive));
                    indicator.classList.toggle('w-7', isActive);
                    indicator.classList.toggle('bg-[#FFD91A]', isActive);
                    indicator.classList.toggle('w-2.5', !isActive);
                    indicator.classList.toggle('bg-white/70', !isActive);
                });
            };

            const nextSlide = () => {
                showSlide(activeIndex + 1);
            };

            const startAutoPlay = () => {
                if (!reducedMotion && timer === null) {
                    timer = window.setInterval(nextSlide, autoInterval);
                }
            };

            const stopAutoPlay = () => {
                if (timer !== null) {
                    window.clearInterval(timer);
                    timer = null;
                }
            };

            const resetAutoPlay = () => {
                stopAutoPlay();
                startAutoPlay();
            };

            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    showSlide(index);
                    resetAutoPlay();
                });
            });

            carousel.addEventListener('mouseenter', stopAutoPlay);
            carousel.addEventListener('mouseleave', startAutoPlay);
            carousel.addEventListener('focusin', stopAutoPlay);
            carousel.addEventListener('focusout', startAutoPlay);

            startAutoPlay();
        });

        // Service cards slide-in from right on scroll
        const slideRightCards = document.querySelectorAll('.service-card-slide-right');
        if (slideRightCards.length) {
            if (reducedMotion || !('IntersectionObserver' in window)) {
                slideRightCards.forEach((card) => card.classList.add('show'));
            } else {
                const cardObserver = new IntersectionObserver((entries, obs) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('show');
                            obs.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.15 });

                slideRightCards.forEach((card) => cardObserver.observe(card));
            }
        }

        const counterElements = document.querySelectorAll('[data-counter]');
        if (!counterElements.length) return;

        const runCounter = (el) => {
            if (el.dataset.counterStarted === 'true') return;
            el.dataset.counterStarted = 'true';

            const target = parseInt(el.getAttribute('data-target') || '0', 10);
            const prefix = el.getAttribute('data-prefix') || '';
            const suffix = el.getAttribute('data-suffix') || '';
            const format = el.getAttribute('data-format') || 'int';

            const formatNumber = (val) => {
                if (format === 'comma') {
                    return val.toLocaleString();
                }
                return String(val);
            };

            if (reducedMotion) {
                el.textContent = `${prefix}${formatNumber(target)}${suffix}`;
                return;
            }

            const duration = 2000;
            const startTime = performance.now();

            const tick = (now) => {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const easeOut = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(easeOut * target);

                el.textContent = `${prefix}${formatNumber(current)}${suffix}`;

                if (progress < 1) {
                    requestAnimationFrame(tick);
                } else {
                    el.textContent = `${prefix}${formatNumber(target)}${suffix}`;
                }
            };

            requestAnimationFrame(tick);
        };

        if (!('IntersectionObserver' in window)) {
            counterElements.forEach(runCounter);
            return;
        }

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    runCounter(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        counterElements.forEach((el) => observer.observe(el));
    })();
</script>
<x-floating-contact-actions />
@endsection
