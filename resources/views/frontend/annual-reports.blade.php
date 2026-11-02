@extends('layouts.app')

@section('title', __('Annual Reports & Institutional Progress'))
@section('meta-description', __('Explore our annual achievements, financial performance, institutional activities, and development journey. Our annual reports reflect our commitment to transparency, accountability, and sustainable cooperative development.'))

@section('content')
<section class="bg-[#14532D] text-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-18 lg:px-8">
        <nav aria-label="{{ __('Breadcrumb') }}" class="mb-6 text-sm text-white/80">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="hover:text-[#FACC15] hover:underline">{{ __('Home') }}</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('reports.index') }}" class="hover:text-[#FACC15] hover:underline">{{ __('Reports') }}</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page">{{ __('Annual Reports') }}</li>
            </ol>
        </nav>
        <div class="max-w-4xl">
            <p class="text-sm font-bold uppercase tracking-[0.2em] text-[#FACC15]">{{ __('Institutional accountability') }}</p>
            <h1 class="mt-3 text-3xl font-extrabold leading-tight sm:text-5xl">{{ __('Annual Reports & Institutional Progress') }}</h1>
            <p class="mt-5 max-w-3xl text-base leading-7 text-white/90">{{ __('Explore our annual achievements, financial performance, institutional activities, and development journey. Our annual reports reflect our commitment to transparency, accountability, and sustainable cooperative development.') }}</p>
            <form method="GET" action="{{ route('annual-reports.index') }}" class="mt-8 grid max-w-4xl gap-3 rounded-2xl border border-white/20 bg-white/10 p-4 backdrop-blur-sm sm:grid-cols-[minmax(0,1fr)_220px_auto]">
                <div>
                    <label for="annual-report-search" class="sr-only">{{ __('Search annual reports') }}</label>
                    <input id="annual-report-search" type="search" name="search" maxlength="100" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('Search annual reports') }}" class="min-h-12 w-full rounded-xl border border-white/30 bg-white px-4 text-sm text-[#182A22] placeholder:text-[#617067] focus:outline-none focus:ring-2 focus:ring-[#FACC15]">
                </div>
                <div>
                    <label for="annual-report-year" class="sr-only">{{ __('Filter by fiscal year') }}</label>
                    <select id="annual-report-year" name="fiscal_year" class="min-h-12 w-full rounded-xl border border-white/30 bg-white px-4 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#FACC15]">
                        <option value="">{{ __('All fiscal years') }}</option>
                        @foreach ($fiscalYears as $fiscalYear)
                            <option value="{{ $fiscalYear->id }}" @selected((string) ($filters['fiscal_year'] ?? '') === (string) $fiscalYear->id)>{{ $fiscalYear->localizedLabel() }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="min-h-12 rounded-xl bg-[#FACC15] px-6 py-3 text-sm font-bold text-[#14532D] transition hover:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-white">{{ __('Search reports') }}</button>
            </form>
        </div>
    </div>
</section>

<main class="mx-auto max-w-7xl space-y-14 px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
    <section aria-labelledby="annual-introduction-heading" class="grid gap-5 rounded-3xl bg-[#F5F8F5] p-6 sm:grid-cols-[1fr_auto] sm:items-center sm:p-8">
        <div>
            <h2 id="annual-introduction-heading" class="text-2xl font-extrabold text-[#182A22]">{{ __('A transparent record of our progress') }}</h2>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-[#617067]">{{ __('Each published annual report brings together verified institutional activities, governance information, financial performance, and future priorities for the stated fiscal year.') }}</p>
        </div>
        <a href="{{ route('annual-reports.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[#14532D] px-5 py-3 text-sm font-semibold text-[#14532D] transition hover:bg-white">{{ __('Browse all publications') }}</a>
    </section>

    <section aria-labelledby="annual-report-topics-heading">
        <div class="mb-5">
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Inside an annual report') }}</p>
            <h2 id="annual-report-topics-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('Institutional progress, from leadership to future plans') }}</h2>
            <p class="mt-2 max-w-3xl text-sm leading-7 text-[#617067]">{{ __('Reports can include the following chapters when administrators provide and approve them. Only published chapters and explicitly verified financial figures appear on an individual report page.') }}</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['title' => __('Leadership and institutional overview'), 'description' => __('Chairperson messages, institutional history, mission, vision, and governance structure.')],
                ['title' => __('Annual activities and membership'), 'description' => __('Documented achievements, member development, training, and community activities.')],
                ['title' => __('Financial and service performance'), 'description' => __('Approved financial information alongside savings, loan, and member-service updates.')],
                ['title' => __('Accountability and future objectives'), 'description' => __('Published audit and governance information, future priorities, and acknowledgements.')],
            ] as $topic)
                <article class="rounded-2xl border border-[#DCE9DF] bg-white p-5 shadow-sm">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[#E7F4EB] text-sm font-extrabold text-[#14532D]" aria-hidden="true">✓</span>
                    <h3 class="mt-4 text-base font-bold text-[#182A22]">{{ $topic['title'] }}</h3>
                    <p class="mt-2 text-sm leading-6 text-[#617067]">{{ $topic['description'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    @if ($latestReport)
        <section aria-labelledby="latest-annual-report-heading">
            <div class="mb-5">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Most recently published') }}</p>
                <h2 id="latest-annual-report-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('Latest Annual Report') }}</h2>
            </div>
            <x-annual-report-card :report="$latestReport" />
        </section>
    @endif

    @if ($featuredReports->isNotEmpty())
        <section aria-labelledby="featured-annual-reports-heading">
            <div class="mb-5">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Selected annual publications') }}</p>
                <h2 id="featured-annual-reports-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('Featured annual reports') }}</h2>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredReports as $report)
                    <x-annual-report-card :report="$report" />
                @endforeach
            </div>
        </section>
    @endif

    <section aria-labelledby="annual-archive-heading">
        <div class="mb-5">
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#27804A]">{{ __('Institutional archive') }}</p>
            <h2 id="annual-archive-heading" class="mt-1 text-2xl font-extrabold text-[#182A22]">{{ __('Previous Annual Reports') }}</h2>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($reports as $report)
                <x-annual-report-card :report="$report" />
            @empty
                <div class="rounded-2xl border border-dashed border-[#DCE9DF] bg-[#F9FBFA] p-8 text-sm text-[#617067] sm:col-span-2 lg:col-span-3">
                    {{ __('No annual reports match your search. Please try another term or fiscal year.') }}
                </div>
            @endforelse
        </div>
        @if ($reports->hasPages())
            <div class="mt-8">{{ $reports->links() }}</div>
        @endif
    </section>
</main>
@endsection
