@extends('layouts.app')

@section('title', 'Become a Member')

@section('content')
<section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="grid overflow-hidden rounded-xl border border-[#DCE9DF] bg-white shadow-[0_25px_50px_rgba(8,113,61,0.06)] lg:grid-cols-[1.1fr_0.9fr]">
        <div class="bg-[#F5F8F6] p-8 lg:p-12">
            <div class="inline-flex rounded-full bg-[#E7F4EB] px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#08713D]">Join the Cooperative</div>
            <h1 class="mt-6 text-4xl font-extrabold text-[#182A22]">Become a Member</h1>
            <p class="mt-4 text-base leading-8 text-[#617067]">
                Join Maharaja Bachat tatha Rin Sahakari Sanstha Ltd. and be part of a community-centered, member-first financial institution focused on savings, opportunity, and shared growth.
            </p>

            <div class="mt-10 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-[#DCE9DF]">
                    <p class="text-sm font-semibold text-[#08713D]">Savings Support</p>
                    <p class="mt-2 text-sm leading-6 text-[#617067]">Build disciplined savings habits through accessible community-based financial services.</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-[#DCE9DF]">
                    <p class="text-sm font-semibold text-[#08713D]">Loan Access</p>
                    <p class="mt-2 text-sm leading-6 text-[#617067]">Access support designed around the needs of members and the wider community.</p>
                </div>
            </div>
        </div>

        <div class="p-8 lg:p-12">
            <h2 class="text-3xl font-bold text-[#182A22]">Create your account</h2>
            <p class="mt-2 text-sm text-[#617067]">Complete the form below to begin your membership journey.</p>

            <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-medium text-[#182A22]">Full name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-2xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20" placeholder="Enter your full name">
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-[#182A22]">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20" placeholder="you@example.com">
                    @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="mb-2 block text-sm font-medium text-[#182A22]">Phone number</label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel" class="w-full rounded-2xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
                    @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="address" class="mb-2 block text-sm font-medium text-[#182A22]">Address</label>
                    <textarea id="address" name="address" rows="3" required autocomplete="street-address" class="w-full rounded-2xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('address') }}</textarea>
                    @error('address') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="identity_document" class="mb-2 block text-sm font-medium text-[#182A22]">Identity document <span class="font-normal text-[#617067]">(optional; PDF or image up to 5 MB)</span></label>
                    <input id="identity_document" type="file" name="identity_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full rounded-2xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] file:mr-4 file:rounded-full file:border-0 file:bg-[#E7F4EB] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[#08713D]">
                    @error('identity_document') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="register-password" class="mb-2 block text-sm font-medium text-[#182A22]">Password</label>
                    <div class="relative">
                        <input id="register-password" type="password" name="password" required autocomplete="new-password" class="w-full rounded-2xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 pr-12 text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20" placeholder="At least 12 characters">
                        <button type="button" data-password-toggle aria-controls="register-password" aria-label="Show password" aria-pressed="false" class="absolute inset-y-0 right-2 inline-flex w-10 items-center justify-center rounded-full text-[#617067] transition hover:bg-[#E7F4EB] hover:text-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/30">
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

                <div>
                    <label for="register-password-confirmation" class="mb-2 block text-sm font-medium text-[#182A22]">Confirm password</label>
                    <div class="relative">
                        <input id="register-password-confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="w-full rounded-2xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 pr-12 text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20" placeholder="Re-enter your password">
                        <button type="button" data-password-toggle aria-controls="register-password-confirmation" aria-label="Show password" aria-pressed="false" class="absolute inset-y-0 right-2 inline-flex w-10 items-center justify-center rounded-full text-[#617067] transition hover:bg-[#E7F4EB] hover:text-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/30">
                            <svg data-eye-visible aria-hidden="true" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/>
                                <circle cx="12" cy="12" r="2.5"/>
                            </svg>
                            <svg data-eye-hidden aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.2A9.8 9.8 0 0 1 12 6c6.1 0 9.5 6 9.5 6a16.5 16.5 0 0 1-3.2 3.7M6.2 6.8C3.8 8.3 2.5 12 2.5 12s3.4 6 9.5 6c1 0 1.9-.2 2.8-.5"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full rounded-full bg-[#08713D] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#04552F]">Create account</button>
            </form>

            <p class="mt-6 text-center text-sm text-[#617067]">
                Already a member?
                <a href="{{ route('login') }}" class="font-semibold text-[#08713D]">Login here</a>
            </p>
        </div>
    </div>
</section>
@endsection
