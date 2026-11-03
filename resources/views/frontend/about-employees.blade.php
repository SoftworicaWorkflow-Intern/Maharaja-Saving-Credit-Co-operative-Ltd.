@extends('layouts.app')

@section('title', __('Employees').' | Maharaj Bachat')

@section('content')
<section id="employees" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <header class="fade-in mx-auto mb-10 max-w-3xl text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Our team') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22] sm:text-5xl">{{ __('Staff') }}</h1>
        <p class="mt-2 text-lg font-semibold text-[#617067]">{{ __('Executive Committee') }}</p>
    </header>

    @php
        $staff = [
            ['image' => 'bhupal-bibari', 'name' => 'भुपाल बिबारी', 'role' => 'अध्यक्ष'],
            ['image' => 'swastik-bahadur-magar', 'name' => 'स्वस्तिक बहादुर मगर', 'role' => 'लघुवित्त सेवा केन्द्र प्रभारी'],
            ['image' => 'shobha-devi-rijal', 'name' => 'शोभादेवी रिजाल', 'role' => 'लेखापाल'],
            ['image' => 'isha-paudel', 'name' => 'ईशा पौडेल', 'role' => 'सेवा सहायक'],
            ['image' => 'hiramunai-shrestha', 'name' => 'हिरामुनाई श्रेष्ठ', 'role' => 'ऋण सहायक'],
            ['image' => 'tanu-k-k', 'name' => 'तनु के.के.', 'role' => 'बचत प्रतिनिधि'],
            ['image' => 'prema-thapa', 'name' => 'प्रेमा थापा', 'role' => 'बचत प्रतिनिधि'],
            ['image' => 'anita-dhakal-gautam', 'name' => 'अनिता ढकाल गौतम', 'role' => 'बचत प्रतिनिधि'],
            ['image' => 'juddha-parajuli', 'name' => 'जुद्दा पराजुली', 'role' => 'बचत प्रतिनिधि'],
            ['image' => 'ramamaya-shah', 'name' => 'रमामाया शाह', 'role' => 'बचत प्रतिनिधि'],
            ['image' => 'kamama-gal-magar-rani', 'name' => 'कमाम्मा गाल मगर (रानी)', 'role' => 'बचत प्रतिनिधि'],
            ['image' => 'shiksha-thapa-magar', 'name' => 'शिक्षा थापा मगर', 'role' => 'बचत प्रतिनिधि'],
            ['image' => 'durgamaya-bahadur-magar', 'name' => 'दुर्गामाया बहादुर मगर', 'role' => 'कार्यालय सहयोगी'],
        ];
    @endphp

    <div class="fade-in grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
        @foreach ($staff as $member)
            <article class="overflow-hidden rounded-xl border border-[#E2E6EC] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="aspect-[1/1.05] overflow-hidden bg-[#F1F4F8]">
                    <img
                        src="{{ asset('images/staff/' . $member['image'] . '.png') }}"
                        alt="{{ __('Portrait of :name', ['name' => $member['name']]) }}"
                        loading="lazy"
                        decoding="async"
                        class="h-full w-full object-cover object-center"
                    >
                </div>
                <div class="min-h-28 px-3 py-4 text-center">
                    <h2 class="text-sm font-bold leading-5 text-[#182A22]">{{ $member['name'] }}</h2>
                    <p class="mt-1 text-xs italic leading-5 text-[#08713D]">{{ $member['role'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endsection
