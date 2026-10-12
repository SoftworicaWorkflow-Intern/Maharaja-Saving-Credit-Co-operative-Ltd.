@extends('layouts.app')

@section('title', ' Login')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="grid overflow-hidden rounded-xl border border-[#DCE9DF] bg-white shadow-[0_25px_50px_rgba(8,113,61,0.06)] lg:grid-cols-2">
        <div class="bg-gradient-to-br from-[#08713D] to-[#0B5F35] p-8 text-white lg:p-12">
            <div class="inline-flex rounded-full bg-[#FFD91A] px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-[#04552F]">Member Portal</div>
            <h1 class="mt-6 text-4xl font-extrabold">Welcome back</h1>
            <p class="mt-4 max-w-md text-sm leading-7 text-green-100">
                Access your savings, loan information, notices, and cooperative updates from one secure member dashboard.
            </p>
            <div class="mt-10 space-y-4 text-sm text-green-100">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#FFD91A] text-[#04552F] font-bold">✓</span>
                    Secure member access
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#FFD91A] text-[#04552F] font-bold">✓</span>
                    Updated notices and account information
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#FFD91A] text-[#04552F] font-bold">✓</span>
                    Community-focused financial service
                </div>
            </div>
        </div>

        <div class="p-8 lg:p-12">
            <h2 class="text-3xl font-bold text-[#182A22]">Login</h2>
            <p class="mt-2 text-sm text-[#617067]">Sign in to continue to your cooperative account.</p>

            <form method="POST" action="{{ route('login.submit') }}" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-medium text-[#182A22]">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20" placeholder="you@example.com">
                    @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="login-password" class="mb-2 block text-sm font-medium text-[#182A22]">Password</label>
                    <div class="relative">
                        <input id="login-password" type="password" name="password" class="w-full rounded-2xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 pr-12 text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20" placeholder="Enter your password">
                        <button type="button" data-password-toggle aria-controls="login-password" aria-label="Show password" aria-pressed="false" class="absolute inset-y-0 right-2 inline-flex w-10 items-center justify-center rounded-full text-[#617067] transition hover:bg-[#E7F4EB] hover:text-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/30">
                            <svg data-eye-visible aria-hidden="true" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/>
                                <circle cx="12" cy="12" r="2.5"/>
                            </svg>
                            <svg data-eye-hidden aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.2A9.8 9.8 0 0 1 12 6c6.1 0 9.5 6 9.5 6a16.5 16.5 0 0 1-3.2 3.7M6.2 6.8C3.8 8.3 2.5 12 2.5 12s3.4 6 9.5 6c1 0 1.9-.2 2.8-.5"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full rounded-full bg-[#08713D] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#04552F]">Login</button>
            </form>

            <p class="mt-6 text-center text-sm text-[#617067]">
                New member?
                <a href="{{ route('register') }}" class="font-semibold text-[#08713D]">Create an account</a>
            </p>
        </div>
    </div>
</section>
@endsection
