@extends('layouts.app')

@section('title', __('Contact'))

@section('content')
<section class="fade-in mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-10 text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Contact') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22]">{{ __('Get in touch with the cooperative') }}</h1>
    </div>
    @if (session('status'))
        <div role="status" class="mb-8 rounded-2xl border border-[#B6D8C0] bg-[#E7F4EB] px-5 py-4 text-sm font-medium text-[#04552F]">{{ session('status') }}</div>
    @endif

    <div class="grid gap-8 md:grid-cols-2">
        <div class="fade-in rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-[#182A22]">{{ __('Office information') }}</h2>
            <ul class="mt-6 space-y-4 text-[#617067]">
                <li><strong class="text-[#182A22]">{{ __('Phone') }}:</strong> <a href="tel:078540530" class="hover:text-[#08713D]">078-540530</a></li>
                <li><strong class="text-[#182A22]">{{ __('Email') }}:</strong> <a href="mailto:saccosmaharaja@gmail.com" class="hover:text-[#08713D]">saccosmaharaja@gmail.com</a></li>
                <li><strong class="text-[#182A22]">{{ __('Address') }}:</strong> {{ __('Kawasoti -2 BP Chowk, Nawalpur') }}</li>
            </ul>
        </div>

        <div class="fade-in rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-[#182A22]">{{ __('Send a message') }}</h2>
            <p class="mt-2 text-sm leading-6 text-[#617067]">{{ __('Send us your question and our team will get back to you.') }}</p>
            <form method="POST" action="{{ route('contact.submit') }}" class="mt-6 space-y-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-[#182A22]">{{ __('Name') }}</label>
                        <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required maxlength="120" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:border-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
                        @error('name') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-[#182A22]">{{ __('Email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required maxlength="255" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:border-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
                        @error('email') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="phone" class="mb-1.5 block text-sm font-medium text-[#182A22]">{{ __('Phone') }} <span class="font-normal text-[#617067]">({{ __('optional') }})</span></label>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" maxlength="40" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:border-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
                    @error('phone') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="subject" class="mb-1.5 block text-sm font-medium text-[#182A22]">{{ __('Subject') }} <span class="font-normal text-[#617067]">({{ __('optional') }})</span></label>
                    <input id="subject" name="subject" value="{{ old('subject') }}" maxlength="160" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:border-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
                    @error('subject') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="message" class="mb-1.5 block text-sm font-medium text-[#182A22]">{{ __('Message') }}</label>
                    <textarea id="message" name="message" rows="4" required maxlength="5000" class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22] focus:border-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">{{ old('message') }}</textarea>
                    @error('message') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="rounded-full bg-[#08713D] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#04552F] focus:outline-none focus:ring-2 focus:ring-[#08713D] focus:ring-offset-2">{{ __('Send message') }}</button>
            </form>
        </div>

        <div class="fade-in overflow-hidden rounded-3xl border border-[#DCE9DF] bg-white p-5 shadow-sm sm:p-8 md:col-span-2">
            <h2 class="text-2xl font-bold text-[#182A22]">{{ __('Find our office') }}</h2>
            <p class="mt-2 text-sm text-[#617067]">{{ __('Kawasoti -2 BP Chowk, Nawalpur') }}</p>
            <div class="mt-5 overflow-hidden rounded-2xl border border-[#DCE9DF]">
                <iframe
                    src="https://www.google.com/maps?q=Kawasoti%20-%202%20BP%20Chowk%2C%20Nawalpur&z=15&output=embed"
                    width="100%"
                    height="360"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="{{ __('Maharaja Bachat Cooperative location') }}">
                </iframe>
            </div>
        </div>
    </div>
</section>
@endsection
