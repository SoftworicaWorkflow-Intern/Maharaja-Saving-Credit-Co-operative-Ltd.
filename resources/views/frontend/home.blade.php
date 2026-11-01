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
                <img data-hero-slide src="{{ asset('images/cooperative-service.png') }}" alt="{{ __('A cooperative staff member serving an elderly Nepali member') }}" class="absolute inset-0 h-full w-full object-cover transition-opacity duration-500" aria-hidden="false">
                <img data-hero-slide src="{{ asset('images/savings-growth.png') }}" alt="{{ __('Growing plants and coins representing savings and financial growth') }}" class="absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-500" aria-hidden="true" hidden>
                <img data-hero-slide src="{{ asset('images/nepali-cooperative-community.svg') }}" alt="{{ __('Illustration of Nepali cooperative members meeting together') }}" class="absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-500" aria-hidden="true" hidden>
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

            <div class="absolute bottom-6 right-6 z-10 flex items-center gap-3 sm:bottom-8 sm:right-8">
                <button type="button" data-hero-previous aria-label="{{ __('Previous image') }}" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/50 bg-[#033B22]/60 text-2xl text-white backdrop-blur-sm transition hover:bg-[#033B22] focus:outline-none focus:ring-2 focus:ring-[#FFD91A]">‹</button>
                <div class="flex items-center gap-2" aria-label="{{ __('Choose an image') }}">
                    <button type="button" data-hero-indicator aria-label="{{ __('Show image :number', ['number' => 1]) }}" aria-current="true" class="h-2.5 w-7 rounded-full bg-[#FFD91A] transition-all"></button>
                    <button type="button" data-hero-indicator aria-label="{{ __('Show image :number', ['number' => 2]) }}" aria-current="false" class="h-2.5 w-2.5 rounded-full bg-white/70 transition-all"></button>
                    <button type="button" data-hero-indicator aria-label="{{ __('Show image :number', ['number' => 3]) }}" aria-current="false" class="h-2.5 w-2.5 rounded-full bg-white/70 transition-all"></button>
                </div>
                <button type="button" data-hero-next aria-label="{{ __('Next image') }}" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/50 bg-[#033B22]/60 text-2xl text-white backdrop-blur-sm transition hover:bg-[#033B22] focus:outline-none focus:ring-2 focus:ring-[#FFD91A]">›</button>
            </div>
        </div>
    </section>

    {{-- OUR IMPACT section --}}
    <section class="fade-in bg-[#08713D] py-16 text-white">
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
                        <span data-counter data-target="15420" data-prefix="" data-suffix="+" data-format="comma">15,420+</span>
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

    {{-- ============================================================
         Financial Services Section — What We Offer (Light Gray Background)
    ============================================================ --}}
    <section id="what-we-offer" class="fade-in border-y border-[#DCE9DF] bg-[#EFF8F1] py-16 sm:py-20" aria-labelledby="what-we-offer-heading">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
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
                <a href="{{ route('services') }}#savings"
                    class="group relative flex flex-col justify-between rounded-3xl p-6 text-left transition-all duration-300 sm:p-8 bg-white border border-[#DCE9DF] text-[#182A22] shadow-sm hover:shadow-xl hover:border-[#08713D]/40 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-[#FFD91A]">
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

                {{-- Loan Support Card --}}
                <a href="{{ route('services') }}#loans"
                    class="group relative flex flex-col justify-between rounded-3xl p-6 text-left transition-all duration-300 sm:p-8 bg-white border border-[#DCE9DF] text-[#182A22] shadow-sm hover:shadow-xl hover:border-[#08713D]/40 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-[#FFD91A]">
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

                {{-- Notices & Updates Card --}}
                <a href="{{ route('notices') }}"
                    class="group relative flex flex-col justify-between rounded-3xl p-6 text-left transition-all duration-300 sm:p-8 bg-white border border-[#DCE9DF] text-[#182A22] shadow-sm hover:shadow-xl hover:border-[#08713D]/40 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-[#FFD91A]">
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
    </section>

    {{-- ============================================================
         Leadership Messages
    ============================================================ --}}
    <section id="leadership-messages" class="fade-in bg-white px-4 py-16 font-['Inter'] sm:px-6 lg:px-8">
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
                        <div class="{{ $portraitFirst ? 'order-1 lg:order-1' : 'order-1 lg:order-2' }} relative min-h-72 overflow-hidden bg-[#E3F1E7] sm:min-h-96 lg:min-h-full">
                            @if ($message['has_portrait'])
                                <img src="{{ route('leadership.portrait', $leader) }}" alt="{{ __('Portrait of :name', ['name' => $leaderName ?: __($message['designation'])]) }}" class="absolute inset-0 h-full w-full object-cover">
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

    <section class="fade-in border-t border-[#DCE9DF] bg-[#EFF8F1] px-4 py-16 sm:px-6 lg:px-8" aria-labelledby="member-testimonials-heading">
    <div class="mx-auto max-w-7xl">
        <div class="mx-auto mb-10 max-w-2xl text-center">
            <p class="inline-flex rounded-full border border-[#08713D]/30 bg-white px-4 py-2 text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Member voices') }}</p>
            <h2 id="member-testimonials-heading" class="mt-3 text-3xl font-bold text-[#04552F] sm:text-4xl">{{ __('What Our Members Say') }}</h2>
            <p class="mt-4 text-sm leading-7 text-[#617067] sm:text-base">{{ __('Real experiences from members who are growing with our cooperative.') }}</p>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            @foreach ([
                ['quote' => 'The cooperative team makes saving simple, clear, and dependable for my family.', 'name' => 'Sita Gurung', 'role' => 'Member since 2018', 'image' => 'https://images.pexels.com/photos/3769021/pexels-photo-3769021.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
                ['quote' => 'I received friendly guidance when I needed credit to expand my small business.', 'name' => 'Ramesh Thapa', 'role' => 'Small business member', 'image' => 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
                ['quote' => 'The service feels personal because the staff understands and supports our community.', 'name' => 'Maya K.C.', 'role' => 'Member since 2020', 'image' => 'https://images.pexels.com/photos/3769021/pexels-photo-3769021.jpeg?auto=compress&cs=tinysrgb&w=150&h=150&fit=crop'],
            ] as $testimonial)
                <figure class="flex h-full flex-col rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-8">
                    <div class="flex gap-1 text-[#FFD91A]" aria-label="5 stars">
                        @for ($star = 0; $star < 5; $star++)
                            <span aria-hidden="true">★</span>
                        @endfor
                    </div>
                    <blockquote class="mt-5 flex-1 text-base leading-7 text-[#344054]">“{{ __($testimonial['quote']) }}”</blockquote>
                    <figcaption class="mt-6 flex items-center gap-3 border-t border-[#DCE9DF] pt-5">
                        <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] }}" class="h-12 w-12 shrink-0 rounded-full object-cover ring-2 ring-[#EFF8F1]">
                        <div>
                            <div class="font-semibold text-[#04552F]">{{ $testimonial['name'] }}</div>
                            <div class="mt-1 text-sm text-[#617067]">{{ __($testimonial['role']) }}</div>
                        </div>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>

    
    </section>
</div>

<script>
    (function () {
        const counterElements = document.querySelectorAll('[data-counter]');
        if (!counterElements.length) return;

        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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
@endsection
