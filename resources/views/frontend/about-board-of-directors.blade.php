@extends('layouts.app')

@section('title', __('Board of Directors').' | Maharaj Bachat')

@section('content')
<section id="board-of-directors" class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
    <header class="fade-in mx-auto mb-10 max-w-3xl text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Cooperative leadership') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22] sm:text-5xl">{{ __('Board of Directors') }}</h1>
        <p class="mt-5 text-lg leading-8 text-[#617067]">{{ __('Meet the people guiding Maharaj Bachat.') }}</p>
    </header>

    @php
        $directors = [
            ['image' => 'devi-maya-pulami-katu-magar', 'name' => 'देविमाया पुलामी कातु मगर', 'role' => 'अध्यक्ष'],
            ['image' => 'bal-krishna-pandey', 'name' => 'बालकृष्ण पाण्डे', 'role' => 'उपाध्यक्ष'],
            ['image' => 'rishi-ram-lamal', 'name' => 'ऋषिराम लामाल', 'role' => 'सचिव'],
            ['image' => 'mahendra-bahadur-thapa-magar', 'name' => 'महेंद्र बहादुर थापा मगर', 'role' => 'कोषाध्यक्ष'],
            ['image' => 'gan-bahadur-sak-magar', 'name' => 'गन बहादुर साक मगर', 'role' => 'सह-सचिव / लेखाजोखा उपसमिति उपसंयोजक'],
            ['image' => 'jasmati-thapa-magar', 'name' => 'जस्मती थापा मगर', 'role' => 'सदस्य / महिला उपसमिति संयोजक'],
            ['image' => 'sushila-devi-lamichhane-thapa-magar', 'name' => 'सुशिला देवी लामिछाने थापा मगर', 'role' => 'सदस्य / शिक्षा उपसमिति संयोजक'],
            ['image' => 'rajesh-kumar-sunam', 'name' => 'राजेश कुमार सुनाम', 'role' => 'सदस्य / ऋण उपसमिति संयोजक'],
            ['image' => 'giri-bahadur-katu-magar', 'name' => 'गिर बहादुर कातु मगर', 'role' => 'सदस्य'],
            ['image' => 'kumisara-thapa', 'name' => 'कुमिसरा थापा', 'role' => 'सदस्य'],
            ['image' => 'ek-narayan-paudel', 'name' => 'एक नारायण पौडेल', 'role' => 'सदस्य'],
            ['image' => 'chet-bahadur-pokhiyal', 'name' => 'चेत बहादुर पोखैयार', 'role' => 'सदस्य'],
        ];
    @endphp

    <div class="fade-in grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
        @foreach ($directors as $director)
            <article class="overflow-hidden rounded-xl border border-[#E2E6EC] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="aspect-[1/1.05] overflow-hidden bg-[#F1F4F8]">
                    <img
                        src="{{ asset('images/board-directors/' . $director['image'] . '.png') }}"
                        alt="{{ __('Portrait of :name', ['name' => $director['name']]) }}"
                        loading="lazy"
                        decoding="async"
                        class="h-full w-full object-cover object-center"
                    >
                </div>
                <div class="min-h-28 px-3 py-4 text-center">
                    <h2 class="text-sm font-bold leading-5 text-[#182A22]">{{ $director['name'] }}</h2>
                    <p class="mt-1 text-xs italic leading-5 text-[#08713D]">{{ $director['role'] }}</p>
                </div>
            </article>
        @endforeach
    </div>

</section>
@endsection
