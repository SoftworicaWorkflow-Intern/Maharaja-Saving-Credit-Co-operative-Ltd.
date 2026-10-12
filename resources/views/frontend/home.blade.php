@extends('layouts.app')

@section('title', 'Maharaj Bachat | Secure Savings. A Stronger Future.')

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
        <section class="px-4 pt-6 sm:px-6 lg:px-8" aria-label="{{ $locale === 'ne' ? 'ताजा समाचार' : 'Latest news' }}">
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

    <section class="px-4 sm:px-6 lg:px-8 {{ $tickerNotices->isNotEmpty() ? 'pb-10 pt-0 lg:pb-14' : 'py-10 lg:py-14' }}">
        <div data-hero-carousel class="relative mx-auto flex min-h-[540px] max-w-7xl items-center overflow-hidden rounded-xl bg-[#04552F] shadow-[0_25px_50px_rgba(8,113,61,0.16)] sm:min-h-[600px]" aria-roledescription="carousel" aria-label="Maharaj Bachat cooperative">
            <div class="absolute inset-0">
                <img data-hero-slide src="{{ asset('images/cooperative-service.png') }}" alt="A cooperative staff member serving an elderly Nepali member" class="absolute inset-0 h-full w-full object-cover transition-opacity duration-500" aria-hidden="false">
                <img data-hero-slide src="{{ asset('images/savings-growth.png') }}" alt="Growing plants and coins representing savings and financial growth" class="absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-500" aria-hidden="true" hidden>
                <img data-hero-slide src="{{ asset('images/nepali-cooperative-community.svg') }}" alt="Illustration of Nepali cooperative members meeting together" class="absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-500" aria-hidden="true" hidden>
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
                <p class="mt-5 max-w-2xl text-lg leading-8 text-white/90">
                    We support responsible savings, accessible credit, and community-focused financial services designed to strengthen every member’s future.
                </p>
            </div>

            <div class="absolute bottom-6 right-6 z-10 flex items-center gap-3 sm:bottom-8 sm:right-8">
                <button type="button" data-hero-previous aria-label="Previous image" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/50 bg-[#033B22]/60 text-2xl text-white backdrop-blur-sm transition hover:bg-[#033B22] focus:outline-none focus:ring-2 focus:ring-[#FFD91A]">‹</button>
                <div class="flex items-center gap-2" aria-label="Choose an image">
                    <button type="button" data-hero-indicator aria-label="Show image 1" aria-current="true" class="h-2.5 w-7 rounded-full bg-[#FFD91A] transition-all"></button>
                    <button type="button" data-hero-indicator aria-label="Show image 2" aria-current="false" class="h-2.5 w-2.5 rounded-full bg-white/70 transition-all"></button>
                    <button type="button" data-hero-indicator aria-label="Show image 3" aria-current="false" class="h-2.5 w-2.5 rounded-full bg-white/70 transition-all"></button>
                </div>
                <button type="button" data-hero-next aria-label="Next image" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/50 bg-[#033B22]/60 text-2xl text-white backdrop-blur-sm transition hover:bg-[#033B22] focus:outline-none focus:ring-2 focus:ring-[#FFD91A]">›</button>
            </div>
        </div>
    </section>

        <section class="border-y border-[#DCE9DF] bg-[#EFF8F1]">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-12 sm:grid-cols-2 sm:px-6 lg:grid-cols-4 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#DCE9DF]">
                <div class="text-sm text-[#617067]">Total Members</div>
                <div class="mt-3 text-3xl font-bold text-[#08713D]">—</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#DCE9DF]">
                <div class="text-sm text-[#617067]">Total Savings</div>
                <div class="mt-3 text-3xl font-bold text-[#08713D]">—</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#DCE9DF]">
                <div class="text-sm text-[#617067]">Total Loans</div>
                <div class="mt-3 text-3xl font-bold text-[#08713D]">—</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#DCE9DF]">
                <div class="text-sm text-[#617067]">Total Branches</div>
                <div class="mt-3 text-3xl font-bold text-[#08713D]">—</div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">What we offer</p>
            <h2 class="mt-3 text-3xl font-bold text-[#182A22]">Financial services built around members</h2>
        </div>
        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm">
                <div class="mb-4 inline-flex rounded-2xl bg-[#EFF8F1] p-3 text-[#08713D]">💰</div>
                <h3 class="text-xl font-semibold text-[#182A22]">Savings Products</h3>
                <p class="mt-3 text-[#617067]">Flexible savings schemes designed to help members build security and long-term financial confidence.</p>
            </div>
            <div class="rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm">
                <div class="mb-4 inline-flex rounded-2xl bg-[#EFF8F1] p-3 text-[#08713D]">🏦</div>
                <h3 class="text-xl font-semibold text-[#182A22]">Loan Support</h3>
                <p class="mt-3 text-[#617067]">Need-based credit options for personal, business, agricultural, and emergency requirements.</p>
            </div>
            <div class="rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm">
                <div class="mb-4 inline-flex rounded-2xl bg-[#EFF8F1] p-3 text-[#08713D]">📣</div>
                <h3 class="text-xl font-semibold text-[#182A22]">Notices & Updates</h3>
                <p class="mt-3 text-[#617067]">Clear information on schemes, announcements, and member notices in one accessible place.</p>
            </div>
        </div>
    </section>

    <section class="bg-[#04552F] py-16 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#FFD91A]">Why members trust us</p>
                    <h2 class="mt-4 text-3xl font-bold">Community-first cooperative values</h2>
                    <p class="mt-4 text-green-100">We combine member-oriented service, transparent guidance, and financially responsible operations to support local prosperity.</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white/5 p-5 ring-1 ring-white/10">
                        <div class="text-2xl font-bold text-[#FFD91A]">Trust</div>
                        <p class="mt-2 text-sm text-green-100">Transparent processes and member care.</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-5 ring-1 ring-white/10">
                        <div class="text-2xl font-bold text-[#FFD91A]">Service</div>
                        <p class="mt-2 text-sm text-green-100">Responsive support for every stage.</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-5 ring-1 ring-white/10">
                        <div class="text-2xl font-bold text-[#FFD91A]">Growth</div>
                        <p class="mt-2 text-sm text-green-100">Savings solutions that grow with you.</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-5 ring-1 ring-white/10">
                        <div class="text-2xl font-bold text-[#FFD91A]">Community</div>
                        <p class="mt-2 text-sm text-green-100">Shared progress and local empowerment.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">Latest updates</p>
                <h2 class="mt-2 text-3xl font-bold text-[#182A22]">Notices & announcements</h2>
            </div>
            <a href="{{ route('notices') }}" class="text-sm font-semibold text-[#08713D]">View all notices</a>
        </div>
        <div class="grid gap-6 lg:grid-cols-3">
            @forelse($featuredNotices as $notice)
                <article class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm">
                    <span class="rounded-full bg-[#EFF8F1] px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-[#08713D]">{{ $notice->category }}</span>
                    <h3 class="mt-4 text-xl font-semibold text-[#182A22]"><a lang="{{ app()->getLocale() }}" href="{{ route('notices.show', $notice) }}" class="hover:text-[#08713D] hover:underline">{{ app()->getLocale() === 'ne' ? ($notice->title_np ?: $notice->title_en) : ($notice->title_en ?: $notice->title_np) }}</a></h3>
                    <p class="mt-3 text-sm text-[#617067]">{{ Str::limit(strip_tags($notice->body_en ?: $notice->body_np), 120) }}</p>
                    <div class="mt-6 text-xs font-medium uppercase tracking-[0.12em] text-[#617067]">{{ $notice->published_at?->format('M d, Y') }}</div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-[#DCE9DF] bg-white p-8 text-[#617067] lg:col-span-3">
                    No published notices available yet.
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
