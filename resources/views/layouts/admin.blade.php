<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <title>@yield('title', __('Admin Panel')) · Maharaj Bachat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Devanagari:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom CSS for Sidebar Collapse Animation -->
    <style>
        .sidebar-transition {
            transition: width 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        /* When body has .sidebar-collapsed, shrink the sidebar on desktop */
        @media (min-width: 1024px) {
            body.sidebar-collapsed .main-sidebar {
                width: 5rem; /* w-20 */
            }
            body.sidebar-collapsed .sidebar-text,
            body.sidebar-collapsed .sidebar-section-title {
                display: none;
            }
            body.sidebar-collapsed .sidebar-link {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }
            body.sidebar-collapsed .sidebar-brand-text {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-[#EFF8F1] font-sans text-slate-800 antialiased sidebar-transition">
    <div class="min-h-screen lg:flex">
        
        <!-- SIDEBAR -->
        <aside class="main-sidebar sticky top-0 hidden h-screen w-72 shrink-0 flex-col overflow-y-auto bg-[#08713D] px-5 py-6 text-white lg:flex sidebar-transition">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center lg:justify-start gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Maharaj Bachat" class="h-12 w-12 shrink-0 rounded-full border border-white/20 bg-white p-1">
                <span class="sidebar-brand-text">
                    <span class="block text-xs font-bold uppercase tracking-[0.18em] text-[#FFD91A]">Maharaj Bachat</span>
                    <span class="mt-1 block text-xs font-medium leading-5">महाराज बचत तथा ऋण सहकारी संस्था लि.</span>
                </span>
            </a>

            <p class="sidebar-section-title mt-10 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-green-100">{{ __('Cooperative administration') }}</p>
            <nav class="mt-3 flex flex-col gap-1" aria-label="{{ __('Admin Panel') }}">
                <a href="{{ route('admin.dashboard') }}" aria-current="page" class="sidebar-link flex items-center gap-3 rounded-xl bg-[#FFD91A] px-4 py-3 text-sm font-bold text-[#04552F] shadow-sm">
                    <span aria-hidden="true" class="shrink-0 text-lg">▦</span>
                    <span class="sidebar-text">{{ __('Dashboard') }}</span>
                </a>
                @if (auth()->user()->hasPermissionTo('applications.view'))
                    <a href="{{ route('admin.applications.index') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        <span aria-hidden="true" class="shrink-0 text-lg">▤</span>
                        <span class="sidebar-text">{{ __('Membership applications') }}</span>
                    </a>
                @endif
                @if (auth()->user()->hasPermissionTo('members.view'))
                    <a href="{{ route('admin.members.index') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        <span aria-hidden="true" class="shrink-0 text-lg">♙</span>
                        <span class="sidebar-text">{{ __('Members') }}</span>
                    </a>
                @endif
                @if (auth()->user()->hasPermissionTo('notices.manage'))
                    <a href="{{ route('admin.notices.index') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        <span aria-hidden="true" class="shrink-0 text-lg">▣</span>
                        <span class="sidebar-text">{{ __('Notices') }}</span>
                    </a>
                @endif
                @if (auth()->user()->hasPermissionTo('staff.manage'))
                    <a href="{{ route('admin.staff.index') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        <span aria-hidden="true" class="shrink-0 text-lg">⚙</span>
                        <span class="sidebar-text">{{ __('Staff & permissions') }}</span>
                    </a>
                    <a href="{{ route('admin.leadership.index') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        <span aria-hidden="true" class="shrink-0 text-lg">♙</span>
                        <span class="sidebar-text">Leadership profiles</span>
                    </a>
                @endif
                @if (auth()->user()->hasPermissionTo('contact_inquiries.manage'))
                    <a href="{{ route('admin.contact-inquiries.index') }}" class="sidebar-link flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        <span aria-hidden="true" class="shrink-0 text-lg">✉</span>
                        <span class="sidebar-text">{{ __('Contact inquiries') }}</span>
                    </a>
                @endif
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="mt-auto pt-8" data-confirm="{{ __('Are you sure you want to log out?') }}" onsubmit="return window.confirm(this.dataset.confirm)">
                @csrf
                <button type="submit" class="sidebar-link flex w-full items-center gap-3 rounded-xl border border-white/25 px-4 py-3 text-left text-sm font-semibold text-white transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-[#FFD91A]">
                    <svg aria-hidden="true" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M18 15l3-3m0 0-3-3m3 3H9"/>
                    </svg>
                    <span class="sidebar-text">{{ __('Logout') }}</span>
                </button>
            </form>
        </aside>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="min-w-0 flex-1">
            <header class="border-b border-[#DCE9DF] bg-white">
                <div class="mx-auto flex min-h-20 max-w-[1600px] items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
                    
                    <!-- LEFT SIDE: Hamburger + Title -->
                    <div class="flex min-w-0 items-center gap-3">
                        
                        <!-- Desktop Hamburger Button -->
                        <button type="button" 
                                id="desktop-sidebar-toggle"
                                aria-label="{{ __('Toggle sidebar') }}" 
                                class="hidden lg:inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-transparent text-[#617067] transition hover:bg-[#EFF8F1] hover:text-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Mobile Menu Details -->
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
                                    <a href="{{ route('admin.leadership.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-[#08713D] hover:bg-[#E7F4EB]">Leadership profiles</a>
                                @endif
                                @if (auth()->user()->hasPermissionTo('contact_inquiries.manage'))
                                    <a href="{{ route('admin.contact-inquiries.index') }}" class="mt-1 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-[#08713D] hover:bg-[#E7F4EB]">{{ __('Contact inquiries') }}</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-[#DCE9DF] pt-3" data-confirm="{{ __('Are you sure you want to log out?') }}" onsubmit="return window.confirm(this.dataset.confirm)">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2 rounded-xl px-4 py-2 text-left text-sm font-semibold text-[#08713D] transition hover:bg-[#E7F4EB] focus:outline-none focus:ring-2 focus:ring-[#08713D]">
                                        <svg aria-hidden="true" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M18 15l3-3m0 0-3-3m3 3H9"/>
                                        </svg>
                                        <span>{{ __('Logout') }}</span>
                                    </button>
                                </form>
                            </nav>
                        </details>

                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-[#617067]">{{ __('Admin Panel') }}</p>
                            <h1 class="truncate text-lg font-bold text-[#182A22] sm:text-xl">@yield('page-title', __('Dashboard'))</h1>
                        </div>
                    </div>

                    <!-- RIGHT SIDE: Realtime, Fullscreen, Profile -->
                    <div class="flex shrink-0 items-center gap-3 sm:gap-5">
                        
                        <!-- Realtime Clock -->
                        <div class="hidden items-center gap-2 rounded-lg bg-[#EFF8F1] px-3 py-1.5 text-sm font-semibold text-[#08713D] md:flex" title="{{ __('Current Local Time') }}">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span id="realtime-clock">--:--:--</span>
                        </div>

                        <!-- Fullscreen Toggle Button -->
                        <button type="button" id="fullscreen-toggle" aria-label="{{ __('Toggle Fullscreen') }}" title="{{ __('Toggle Fullscreen') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-[#DCE9DF] bg-white text-[#617067] transition hover:bg-[#EFF8F1] hover:text-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]">
                            <svg id="fs-icon-enter" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                            <svg id="fs-icon-exit" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4H4v5h5zm0 0L4 4m11 5h5V4h-5v5zm0 0l5-5M9 15v5H4v-5h5zm0 0l-5 5m11-5h5v5h-5v-5zm0 0l5 5" />
                            </svg>
                        </button>

                        <!-- Profile Info -->
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

    <!-- JAVASCRIPT FOR NEW FEATURES -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Sidebar Minimize / Maximize Logic
            const sidebarToggle = document.getElementById('desktop-sidebar-toggle');
            const body = document.body;
            
            // Check local storage for saved state
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                body.classList.add('sidebar-collapsed');
            }

            sidebarToggle.addEventListener('click', () => {
                const isCollapsed = body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', isCollapsed);
            });

            // 2. Fullscreen Logic
            const fsToggleBtn = document.getElementById('fullscreen-toggle');
            const fsIconEnter = document.getElementById('fs-icon-enter');
            const fsIconExit = document.getElementById('fs-icon-exit');

            fsToggleBtn.addEventListener('click', () => {
                if (!document.fullscreenElement) {
                    // Enter Fullscreen
                    document.documentElement.requestFullscreen().catch(err => {
                        console.error(`Error attempting to enable fullscreen: ${err.message}`);
                    });
                } else {
                    // Exit Fullscreen
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            });

            // Update icons when fullscreen changes (e.g. user presses Esc key)
            document.addEventListener('fullscreenchange', () => {
                if (document.fullscreenElement) {
                    fsIconEnter.classList.add('hidden');
                    fsIconExit.classList.remove('hidden');
                } else {
                    fsIconEnter.classList.remove('hidden');
                    fsIconExit.classList.add('hidden');
                }
            });

            // 3. Realtime Clock Logic
            const clockElement = document.getElementById('realtime-clock');
            
            function updateClock() {
                if (!clockElement) return;
                const now = new Date();
                // Format time as HH:MM:SS AM/PM
                clockElement.textContent = now.toLocaleTimeString([], { 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    second: '2-digit' 
                });
            }
            
            // Run immediately, then update every 1000ms (1 second)
            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
</body>
</html>