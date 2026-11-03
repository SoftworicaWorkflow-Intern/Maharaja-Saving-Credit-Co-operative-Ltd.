@extends('layouts.app')

@section('title', __('About Us'))

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-10 text-center fade-in">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('About the Cooperative') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-[#182A22]">{{ __('About Maharaj Bachat') }}</h1>
    </div>

    <div class="mx-auto grid max-w-5xl gap-8">
        <section class="fade-in rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm sm:p-10">
            <h2 class="text-2xl font-bold text-[#182A22]">{{ __('Our history') }}</h2>
            <p class="mt-4 text-base leading-8 text-[#617067]">{{ __('Built on the cooperative idea that financial progress is stronger when members share ownership and responsibility, Maharaj Bachat tatha Rin Sahakari Sanstha Ltd. has focused its work on member savings, responsible credit, and community development.') }}</p>
            <p class="mt-4 text-base leading-8 text-[#617067]">{{ __('Today, this work continues through accessible financial services, transparent governance, and a commitment to the welfare of members and the wider community.') }}</p>
        </section>

        <section class="fade-in rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm sm:p-10">
            <h2 class="text-2xl font-bold text-[#182A22]">{{ __('Our introduction') }}</h2>
            <p class="mt-4 text-base leading-8 text-[#617067]">{{ __('Maharaj Bachat tatha Rin Sahakari Sanstha Ltd. is committed to promoting savings discipline, responsible lending, and member development through cooperative principles and ethical service.') }}</p>
            <p class="mt-4 text-base leading-8 text-[#617067]">{{ __('The cooperative works to strengthen individuals and communities by creating practical, accessible financial services centered on trust, transparency, and member welfare.') }}</p>
        </section>

        <section class="fade-in rounded-3xl border border-[#DCE9DF] bg-[#F5F8F6] p-8 sm:p-10">
            <div class="grid gap-8 md:grid-cols-[0.8fr_1.2fr] md:items-center">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.15em] text-[#08713D]">{{ __('Local service network') }}</p>
                    <h2 class="mt-3 text-2xl font-bold text-[#182A22]">{{ __('Our branches') }}</h2>
                    <p class="mt-3 leading-7 text-[#617067]">{{ __('Our branch network brings cooperative financial services closer to members and local communities.') }}</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-[#DCE9DF] bg-white p-5">
                        <p class="text-sm font-semibold text-[#08713D]">{{ __('Head office') }}</p>
                        <p class="mt-2 text-base font-bold text-[#182A22]">Kawasoti-2 BP Chowk</p>
                        <p class="mt-1 text-sm text-[#617067]">Nawalpur</p>
                    </div>
                    <div class="rounded-2xl border border-[#DCE9DF] bg-white p-5 sm:col-span-2">
                        <p class="text-sm font-semibold text-[#08713D]">{{ __('Branch locations') }}</p>
                        <ul class="mt-3 grid gap-2 sm:grid-cols-2">
                            <li class="rounded-lg bg-[#F5F8F6] px-3 py-2 text-sm font-semibold text-[#182A22]">Kawasoti-3</li>
                            <li class="rounded-lg bg-[#F5F8F6] px-3 py-2 text-sm font-semibold text-[#182A22]">Kawasoti-16</li>
                            <li class="rounded-lg bg-[#F5F8F6] px-3 py-2 text-sm font-semibold text-[#182A22]">Danda</li>
                            <li class="rounded-lg bg-[#F5F8F6] px-3 py-2 text-sm font-semibold text-[#182A22]">Rajar</li>
                            <li class="rounded-lg bg-[#F5F8F6] px-3 py-2 text-sm font-semibold text-[#182A22]">Chormara</li>
                            <li class="rounded-lg bg-[#F5F8F6] px-3 py-2 text-sm font-semibold text-[#182A22]">Pragatinagar</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section id="vision-mission" class="fade-in" aria-labelledby="about-vision-mission-heading">
            <div class="mb-5">
                <p class="text-sm font-semibold uppercase tracking-[0.15em] text-[#08713D]">{{ __('Our direction') }}</p>
                <h2 id="about-vision-mission-heading" class="mt-2 text-2xl font-bold text-[#182A22]">{{ __('Vision & Mission') }}</h2>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                <article class="rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm">
                    <h3 class="text-xl font-bold text-[#182A22]">{{ __('Vision') }}</h3>
                    <p class="mt-4 leading-8 text-[#617067]">{{ __('A financially sound business organization based in the community with quality service.') }}</p>
                </article>
                <article class="rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm">
                    <h3 class="text-xl font-bold text-[#182A22]">{{ __('Mission') }}</h3>
                    <p class="mt-4 leading-8 text-[#617067]">{{ __('To provide economic, social and cultural development through efficient financial leadership, efficient management, policy, method and technology, and by providing financial and non-financial services to the members and the community.') }}</p>
                </article>
            </div>
        </section>

        <div class="grid gap-6 sm:grid-cols-2">
            <section class="fade-in rounded-3xl bg-[#EFF8F1] p-8">
                <h2 class="text-xl font-semibold text-[#182A22]">{{ __('Core values') }}</h2>
                <ul class="mt-4 space-y-3 text-[#617067]">
                    <li>{{ __('Member welfare') }}</li>
                    <li>{{ __('Transparency') }}</li>
                    <li>{{ __('Trust and accountability') }}</li>
                    <li>{{ __('Community service') }}</li>
                </ul>
            </section>
            <section class="fade-in rounded-3xl bg-[#EFF8F1] p-8">
                <h2 class="text-xl font-semibold text-[#182A22]">{{ __('Objectives') }}</h2>
                <ul class="mt-4 space-y-3 text-[#617067]">
                    <li>{{ __('Expand accessible savings') }}</li>
                    <li>{{ __('Support productive lending') }}</li>
                    <li>{{ __('Promote financial literacy') }}</li>
                    <li>{{ __('Strengthen local ownership') }}</li>
                </ul>
            </section>
        </div>

        <section class="fade-in rounded-3xl border border-[#DCE9DF] bg-white p-8 shadow-sm sm:p-10">
            <h2 class="text-2xl font-bold text-[#182A22]">{{ __('Organizational structure') }}</h2>
            <p class="mt-4 leading-7 text-[#617067]">{{ __('The cooperative is led by the board and management team in accordance with cooperative governance and member oversight.') }}</p>
        </section>
    </div>
</section>
@endsection
