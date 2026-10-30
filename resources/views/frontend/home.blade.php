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
                <p class="mt-5 max-w-2xl text-lg leading-8 text-white/90">{{ __('We support responsible savings, accessible credit, and community-focused financial services designed to strengthen every member’s future.') }}</p>
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

        <section class="fade-in border-y border-[#DCE9DF] bg-[#EFF8F1]">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-12 sm:grid-cols-2 sm:px-6 lg:grid-cols-4 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#DCE9DF]">
                <div class="text-sm text-[#617067]">{{ __('Total Members') }}</div>
                <div class="mt-3 text-3xl font-bold text-[#08713D]">—</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#DCE9DF]">
                <div class="text-sm text-[#617067]">{{ __('Total Savings') }}</div>
                <div class="mt-3 text-3xl font-bold text-[#08713D]">—</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#DCE9DF]">
                <div class="text-sm text-[#617067]">{{ __('Total Loans') }}</div>
                <div class="mt-3 text-3xl font-bold text-[#08713D]">—</div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-[#DCE9DF]">
                <div class="text-sm text-[#617067]">{{ __('Total Branches') }}</div>
                <div class="mt-3 text-3xl font-bold text-[#08713D]">—</div>
            </div>
        </div>
    </section>

    <section class="fade-in mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('What we offer') }}</p>
            <h2 class="mt-3 text-3xl font-bold text-[#182A22]">{{ __('Financial services built around members') }}</h2>
        </div>
        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm">
                <div class="mb-4 inline-flex rounded-2xl bg-[#EFF8F1] p-3 text-[#08713D]">💰</div>
                <h3 class="text-xl font-semibold text-[#182A22]">{{ __('Savings Products') }}</h3>
                <p class="mt-3 text-[#617067]">{{ __('Flexible savings schemes designed to help members build security and long-term financial confidence.') }}</p>
            </div>
            <div class="rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm">
                <div class="mb-4 inline-flex rounded-2xl bg-[#EFF8F1] p-3 text-[#08713D]">🏦</div>
                <h3 class="text-xl font-semibold text-[#182A22]">{{ __('Loan Support') }}</h3>
                <p class="mt-3 text-[#617067]">{{ __('Need-based credit options for personal, business, agricultural, and emergency requirements.') }}</p>
            </div>
            <div class="rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm">
                <div class="mb-4 inline-flex rounded-2xl bg-[#EFF8F1] p-3 text-[#08713D]">📣</div>
                <h3 class="text-xl font-semibold text-[#182A22]">{{ __('Notices & Updates') }}</h3>
                <p class="mt-3 text-[#617067]">{{ __('Clear information on schemes, announcements, and member notices in one accessible place.') }}</p>
            </div>
        </div>
    </section>

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

        <!-- Photo Gallery Section -->
    <section class="fade-in border-t border-[#DCE9DF] bg-[#EFF8F1] px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-10 text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Our Gallery') }}</p>
                <h2 class="mt-3 text-3xl font-bold text-[#182A22]">{{ __('Moments from our community') }}</h2>
                <p class="mt-4 text-[#617067] max-w-2xl mx-auto">{{ __('Glimpses of our cooperative activities, member interactions, and community programs.') }}</p>
            </div>

            @php
                $galleryImages = [
                    ['src' => 'images/gallery/gallery-1.jpg', 'alt' => __('Members receiving support packages'), 'caption' => __('Community Support')],
                    ['src' => 'images/gallery/gallery-2.jpg', 'alt' => __('Staff interacting with a mother and child'), 'caption' => __('Member Care')],
                    ['src' => 'images/gallery/gallery-3.jpg', 'alt' => __('Annual general meeting group photo'), 'caption' => __('Annual General Meeting')],
                    ['src' => 'images/gallery/gallery-4.jpg', 'alt' => __('Staff posing with project review banner'), 'caption' => __('Project Review Workshop')],
                    ['src' => 'images/gallery/gallery-5.jpg', 'alt' => __('Community gathering in a rural area'), 'caption' => __('Rural Outreach')],
                    ['src' => 'images/gallery/gallery-6.jpg', 'alt' => __('Large group photo of members and staff'), 'caption' => __('Team & Members')],
                ];
            @endphp

            <!-- Mobile: Horizontal Scroll | Desktop: Single Row Grid -->
            <div class="flex snap-x snap-mandatory gap-4 overflow-x-auto pb-4 lg:grid lg:grid-cols-6 lg:gap-4 lg:overflow-visible lg:pb-0 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                @foreach ($galleryImages as $image)
                    <div class="group relative aspect-[3/4] w-64 shrink-0 snap-start overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-[#DCE9DF] sm:w-72 lg:w-auto lg:shrink">
                        <img src="{{ asset($image['src']) }}" 
                             alt="{{ $image['alt'] }}" 
                             loading="lazy"
                             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 flex items-end bg-gradient-to-t from-[#033B22]/80 via-[#033B22]/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <div class="p-4 text-white transform translate-y-4 transition-transform duration-300 group-hover:translate-y-0">
                                <span class="inline-block rounded-full bg-[#FFD91A] px-2.5 py-1 text-[10px] font-bold text-[#04552F] mb-1.5 uppercase tracking-wider">
                                    {{ __('View') }}
                                </span>
                                <h3 class="text-sm font-semibold leading-tight">{{ $image['caption'] }}</h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Optional: Scroll hint for mobile -->
            <p class="mt-2 text-center text-xs text-[#617067] lg:hidden">
                {{ __('Swipe to see more →') }}
            </p>
        </div>
    </section>
</div>


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

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Latest updates') }}</p>
                <h2 class="mt-2 text-3xl font-bold text-[#182A22]">{{ __('Notices & announcements') }}</h2>
            </div>
            <a href="{{ route('notices') }}" class="text-sm font-semibold text-[#08713D]">{{ __('View all notices') }}</a>
        </div>
        <div class="grid gap-6 lg:grid-cols-3">
            @forelse($featuredNotices as $notice)
                <article class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm">
                    <span class="rounded-full bg-[#EFF8F1] px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-[#08713D]">{{ $notice->category }}</span>
                    <h3 class="mt-4 text-xl font-semibold text-[#182A22]"><a lang="{{ app()->getLocale() }}" href="{{ route('notices.show', $notice) }}" class="hover:text-[#08713D] hover:underline">{{ app()->getLocale() === 'ne' ? ($notice->title_np ?: $notice->title_en) : ($notice->title_en ?: $notice->title_np) }}</a></h3>
                    <p class="mt-3 text-sm text-[#617067]">{{ Str::limit(strip_tags(app()->getLocale() === 'ne' ? ($notice->body_np ?: $notice->body_en) : ($notice->body_en ?: $notice->body_np)), 120) }}</p>
                    <div class="mt-6 text-xs font-medium uppercase tracking-[0.12em] text-[#617067]">{{ $notice->published_at?->format('M d, Y') }}</div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-[#DCE9DF] bg-white p-8 text-[#617067] lg:col-span-3">
                    {{ __('No published notices available yet.') }}
                </div>
            @endforelse
        </div>
    </section>


@endsection
