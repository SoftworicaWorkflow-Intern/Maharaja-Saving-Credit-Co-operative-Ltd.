<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Admin Panel')) · Maharaj Bachat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#EFF8F1] font-sans text-slate-800 antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="sticky top-0 hidden h-screen w-72 shrink-0 flex-col overflow-y-auto bg-[#08713D] px-5 py-6 text-white lg:flex">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Maharaj Bachat" class="h-12 w-12 rounded-full border border-white/20 bg-white p-1">
                <span>
                    <span class="block text-xs font-bold uppercase tracking-[0.18em] text-[#FFD91A]">Maharaj Bachat</span>
                    <span class="mt-1 block text-xs font-medium leading-5">महाराज बचत तथा ऋण सहकारी संस्था लि.</span>
                </span>
            </a>

            <p class="mt-10 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-green-100">{{ __('Cooperative administration') }}</p>
            <nav class="mt-3" aria-label="{{ __('Admin Panel') }}">
                <a href="{{ route('admin.dashboard') }}" aria-current="page" class="flex items-center gap-3 rounded-xl bg-[#FFD91A] px-4 py-3 text-sm font-bold text-[#04552F] shadow-sm">
                    <span aria-hidden="true">▦</span>
                    {{ __('Dashboard') }}
                </a>
                @if (auth()->user()->hasPermissionTo('applications.view'))
                    <a href="{{ route('admin.applications.index') }}" class="mt-2 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        <span aria-hidden="true">▤</span>
                        {{ __('Membership applications') }}
                    </a>
                @endif
                @if (auth()->user()->hasPermissionTo('members.view'))
                    <a href="{{ route('admin.members.index') }}" class="mt-2 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        <span aria-hidden="true">♙</span>
                        {{ __('Members') }}
                    </a>
                @endif
                @if (auth()->user()->hasPermissionTo('notices.manage'))
                    <a href="{{ route('admin.notices.index') }}" class="mt-2 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        <span aria-hidden="true">▣</span>
                        {{ __('Notices') }}
                    </a>
                @endif
                @if (auth()->user()->hasPermissionTo('staff.manage'))
                    <a href="{{ route('admin.staff.index') }}" class="mt-2 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        <span aria-hidden="true">⚙</span>
                        {{ __('Staff & permissions') }}
                    </a>
                @endif
                @if (auth()->user()->hasPermissionTo('contact_inquiries.manage'))
                    <a href="{{ route('admin.contact-inquiries.index') }}" class="mt-2 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        <span aria-hidden="true">✉</span>
                        {{ __('Contact inquiries') }}
                    </a>
                @endif
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="mt-auto pt-8">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-white/25 px-4 py-3 text-left text-sm font-semibold text-white transition hover:bg-white/10">{{ __('Logout') }}</button>
            </form>
        </aside>

        <div class="min-w-0 flex-1">
            <header class="border-b border-[#DCE9DF] bg-white">
                <div class="mx-auto flex min-h-20 max-w-[1600px] items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <details class="relative lg:hidden">
                            <summary class="cursor-pointer list-none rounded-xl border border-[#DCE9DF] px-3 py-2 text-sm font-semibold text-[#08713D]">☰ <span class="sr-only">{{ __('Dashboard') }}</span></summary>
                            <nav class="absolute left-0 top-12 z-20 w-64 rounded-2xl border border-[#DCE9DF] bg-white p-3 shadow-xl" aria-label="{{ __('Admin Panel') }}">
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl bg-[#E7F4EB] px-4 py-3 text-sm font-bold text-[#04552F]">{{ __('Dashboard') }}</a>
                                @if (auth()->user()->hasPermissionTo('applications.view'))
                                    <a href="{{ route('admin.applications.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-[#08713D] hover:bg-[#E7F4EB]">{{ __('Membership applications') }}</a>
                                @endif
                                @if (auth()->user()->hasPermissionTo('members.view'))
                                    <a href="{{ route('admin.members.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-[#08713D] hover:bg-[#E7F4EB]">{{ __('Members') }}</a>
                                @endif
                                @if (auth()->user()->hasPermissionTo('notices.manage'))
                                    <a href="{{ route('admin.notices.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-[#08713D] hover:bg-[#E7F4EB]">{{ __('Notices') }}</a>
                                @endif
                                @if (auth()->user()->hasPermissionTo('staff.manage'))
                                    <a href="{{ route('admin.staff.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-[#08713D] hover:bg-[#E7F4EB]">{{ __('Staff & permissions') }}</a>
                                @endif
                                @if (auth()->user()->hasPermissionTo('contact_inquiries.manage'))
                                    <a href="{{ route('admin.contact-inquiries.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-[#08713D] hover:bg-[#E7F4EB]">{{ __('Contact inquiries') }}</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-[#DCE9DF] pt-3">
                                    @csrf
                                    <button type="submit" class="w-full rounded-xl px-4 py-2 text-left text-sm font-semibold text-[#08713D]">{{ __('Logout') }}</button>
                                </form>
                            </nav>
                        </details>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#617067]">{{ __('Admin Panel') }}</p>
                            <h1 class="truncate text-lg font-bold text-[#182A22] sm:text-xl">@yield('page-title', __('Dashboard'))</h1>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-3">
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-semibold text-[#182A22]">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-[#617067]">{{ __('Administrator') }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-[1600px] px-4 py-8 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
