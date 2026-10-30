<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#EFF8F1] text-slate-800 antialiased">
    <div class="w-full bg-[#04552F] print:hidden">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" aria-label="{{ __('Maharaj Bachat home') }}">
                <img src="{{ asset('images/branding-banner.jpeg') }}" alt="{{ __('Maharaj Bachat tatha Rin Sahakari Sanstha Ltd.') }}" class="block h-16 w-auto max-w-[min(76vw,820px)] object-contain object-left sm:h-24">
            </a>
            <form method="GET" action="{{ route('notices') }}" role="search" class="flex w-full max-w-56 items-center gap-2 rounded-full border border-white/30 bg-white px-2 py-1 focus-within:ring-2 focus-within:ring-[#FFD91A] sm:translate-y-2">
                <label for="site-search" class="sr-only">{{ __('Search notices') }}</label>
                <input id="site-search" type="search" name="search" value="{{ request()->routeIs('notices') ? request('search') : '' }}" placeholder="{{ __('Search notices') }}" class="min-w-0 flex-1 border-0 bg-transparent px-3 py-2 text-sm text-[#182A22] outline-none placeholder:text-[#617067]">
                <button type="submit" aria-label="{{ __('Search notices') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#FFD91A] text-[#04552F] transition hover:bg-white">
                    <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path stroke-linecap="round" d="m16 16 4 4"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    <header class="sticky top-0 z-50 border-b border-[#DCE9DF] bg-[#08713D]/95 text-white backdrop-blur-sm print:hidden">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3 xl:shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Maharaj Bachat" class="h-12 w-12 rounded-full border border-white/20 bg-white p-1">
                <div>
                    <div class="text-xs uppercase tracking-[0.2em] text-[#FFD91A]">Maharaj Bachat</div>
                    <div class="text-xs font-semibold leading-tight xl:whitespace-nowrap">महाराज बचत तथा ऋण सहकारी संस्था लि.</div>
                </div>
            </a>
            <nav class="hidden shrink-0 items-center gap-4 whitespace-nowrap text-sm font-medium xl:flex">
                <a href="{{ route('home') }}" class="whitespace-nowrap hover:text-[#FFD91A] {{ request()->routeIs('home') ? 'text-[#FFD91A]' : '' }}">{{ __('Home') }}</a>
                <a href="{{ route('about') }}" class="whitespace-nowrap hover:text-[#FFD91A] {{ request()->routeIs('about') ? 'text-[#FFD91A]' : '' }}">{{ __('About Us') }}</a>
                <a href="{{ route('savings') }}" class="whitespace-nowrap hover:text-[#FFD91A] {{ request()->routeIs('savings') ? 'text-[#FFD91A]' : '' }}">{{ __('Savings') }}</a>
                <a href="{{ route('loans') }}" class="whitespace-nowrap hover:text-[#FFD91A] {{ request()->routeIs('loans') ? 'text-[#FFD91A]' : '' }}">{{ __('Loans') }}</a>
                <a href="{{ route('notices') }}" class="whitespace-nowrap hover:text-[#FFD91A] {{ request()->routeIs('notices') ? 'text-[#FFD91A]' : '' }}">{{ __('Notices') }}</a>
                <a href="{{ route('contact') }}" class="whitespace-nowrap hover:text-[#FFD91A] {{ request()->routeIs('contact') ? 'text-[#FFD91A]' : '' }}">{{ __('Contact') }}</a>
            </nav>
            <div class="hidden shrink-0 items-center gap-2 xl:flex">
                <div class="inline-flex items-center gap-1 rounded-full bg-[#E3130A] p-1 text-xs font-semibold" role="group" aria-label="{{ __('Language') }}">
                    <form method="POST" action="{{ route('language.switch', ['locale' => 'en']) }}">
                        @csrf
                        <button type="submit" lang="en" aria-pressed="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}" class="rounded-full px-2 py-1 {{ app()->getLocale() === 'en' ? 'bg-white text-[#344054]' : 'text-white' }}">Eng</button>
                    </form>
                    <form method="POST" action="{{ route('language.switch', ['locale' => 'ne']) }}">
                        @csrf
                        <button type="submit" lang="ne" aria-pressed="{{ app()->getLocale() === 'ne' ? 'true' : 'false' }}" class="rounded-full px-2 py-1 {{ app()->getLocale() === 'ne' ? 'bg-white text-[#344054]' : 'text-white' }}">नेपाली</button>
                    </form>
                </div>
                <a href="{{ route('login') }}" class="rounded-full bg-[#FFD91A] px-4 py-2 text-sm font-semibold text-[#04552F] shadow-sm">{{ __('Login') }}</a>
                <a href="{{ route('register') }}" class="whitespace-nowrap rounded-full bg-white px-3 py-2 text-sm font-semibold text-[#04552F]">{{ __('Become a Member') }}</a>
            </div>
            <button type="button" data-mobile-nav-toggle aria-controls="mobile-navigation" aria-expanded="false" aria-label="{{ __('Open navigation') }}" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-white/30 xl:hidden">
                <svg data-menu-open aria-hidden="true" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg data-menu-close aria-hidden="true" class="hidden h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" d="m6 6 12 12M18 6 6 18"></path>
                </svg>
            </button>
        </div>
        <nav id="mobile-navigation" data-mobile-nav hidden class="hidden border-t border-white/15 px-4 py-3 xl:hidden" aria-label="{{ __('Mobile navigation') }}">
            <div class="mx-auto flex max-w-7xl flex-col gap-1">
                <a href="{{ route('home') }}" class="rounded-lg px-4 py-3 text-sm font-medium text-white hover:bg-white/10">{{ __('Home') }}</a>
                <a href="{{ route('about') }}" class="rounded-lg px-4 py-3 text-sm font-medium text-white hover:bg-white/10">{{ __('About Us') }}</a>
                <a href="{{ route('savings') }}" class="rounded-lg px-4 py-3 text-sm font-medium text-white hover:bg-white/10">{{ __('Savings') }}</a>
                <a href="{{ route('loans') }}" class="rounded-lg px-4 py-3 text-sm font-medium text-white hover:bg-white/10">{{ __('Loans') }}</a>
                <a href="{{ route('notices') }}" class="rounded-lg px-4 py-3 text-sm font-medium text-white hover:bg-white/10">{{ __('Notices') }}</a>
                <a href="{{ route('contact') }}" class="rounded-lg px-4 py-3 text-sm font-medium text-white hover:bg-white/10">{{ __('Contact') }}</a>
                <div class="mt-2 flex items-center justify-between border-t border-white/15 py-3">
                    <span class="text-sm font-medium">{{ __('Language') }}</span>
                    <div class="inline-flex items-center gap-1 rounded-full bg-[#E3130A] p-1 text-xs font-semibold" role="group" aria-label="{{ __('Language') }}">
                        <form method="POST" action="{{ route('language.switch', ['locale' => 'en']) }}">
                            @csrf
                            <button type="submit" lang="en" aria-pressed="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}" class="rounded-full px-2 py-1 {{ app()->getLocale() === 'en' ? 'bg-white text-[#344054]' : 'text-white' }}">Eng</button>
                        </form>
                        <form method="POST" action="{{ route('language.switch', ['locale' => 'ne']) }}">
                            @csrf
                            <button type="submit" lang="ne" aria-pressed="{{ app()->getLocale() === 'ne' ? 'true' : 'false' }}" class="rounded-full px-2 py-1 {{ app()->getLocale() === 'ne' ? 'bg-white text-[#344054]' : 'text-white' }}">नेपाली</button>
                        </form>
                    </div>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('login') }}" class="rounded-lg bg-[#FFD91A] px-4 py-3 text-center text-sm font-semibold text-[#04552F]">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-white px-4 py-3 text-center text-sm font-semibold text-[#04552F]">{{ __('Become a Member') }}</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="border-t border-[#DCE9DF] bg-[#04552F] text-white print:hidden">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-4 lg:px-8">
            <div>
                <div class="mb-4 flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Cooperative logo" class="h-12 w-12 rounded-full bg-white p-1">
                    <div>
                        <div class="text-xs uppercase tracking-[0.22em] text-[#FFD91A]">Maharaj Bachat</div>
                        <div class="text-sm font-semibold">महाराज बचत तथा ऋण सहकारी संस्था लि.</div>
                    </div>
                </div>
                <p class="text-sm text-green-100">Strong Savings, Prosperous Future</p>
            </div>
            <div>
                <h3 class="mb-4 text-lg font-semibold text-[#FFD91A]">{{ __('Quick Links') }}</h3>
                <ul class="space-y-2 text-sm text-green-100">
                    <li><a href="{{ route('about') }}">{{ __('About Us') }}</a></li>
                    <li><a href="{{ route('savings') }}">{{ __('Savings') }}</a></li>
                    <li><a href="{{ route('loans') }}">{{ __('Loans') }}</a></li>
                    <li><a href="{{ route('notices') }}">{{ __('Notices') }}</a></li>
                </ul>
            </div>
            <div>
                <h3 class="mb-4 text-lg font-semibold text-[#FFD91A]">Contact</h3>
                <ul class="space-y-2 text-sm text-green-100">
                    <li>Phone: 078-540530</li>
                    <li>Email: saccosmaharaja@gmail.com</li>
                    <li>Address: Kawasoti -2 BP Chowk, Nawalpur</li>
                </ul>
            </div>
            <div>
                <h3 class="mb-4 text-lg font-semibold text-[#FFD91A]">Legal</h3>
                <ul class="space-y-2 text-sm text-green-100">
                    <li>Registered Cooperative</li>
                    <li>Member-first policy</li>
                    <li>Privacy & data protection</li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
