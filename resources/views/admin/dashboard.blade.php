@extends('layouts.admin')

@section('title', __('Dashboard'))
@section('page-title', __('Dashboard'))

@section('content')
<div class="mb-8">
    <p class="text-sm font-semibold text-[#08713D]">{{ __('Financial cooperative management') }}</p>
    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-[#182A22]">{{ __('Welcome, :name', ['name' => $administrator->name]) }}</h2>
    <p class="mt-2 max-w-2xl text-sm leading-6 text-[#617067]">{{ __('Review cooperative activity and open the management tools available to your account.') }}</p>
</div>

<section class="mb-10" aria-label="{{ __('Operational overview') }}">
    <h3 class="mb-4 text-lg font-bold text-[#182A22]">{{ __('Operational overview') }}</h3>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($operationalStats as $stat)
            <a href="{{ route($stat['route']) }}" class="rounded-xl border border-[#DCE9DF] bg-white p-5 shadow-sm transition hover:border-[#08713D] hover:shadow-md">
                <p class="text-sm font-medium text-[#617067]">{{ $stat['label'] }}</p>
                <p class="mt-2 text-3xl font-bold text-[#08713D]">{{ number_format($stat['count']) }}</p>
                <p class="mt-2 text-sm text-[#617067]">{{ $stat['description'] }}</p>
            </a>
        @empty
            <p class="rounded-xl border border-[#DCE9DF] bg-white p-5 text-sm text-[#617067]">{{ __('No management statistics are available for this account.') }}</p>
        @endforelse
    </div>
</section>

<section class="mb-10" aria-label="{{ __('Quick access') }}">
    <h3 class="mb-4 text-lg font-bold text-[#182A22]">{{ __('Quick access') }}</h3>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @if ($administrator->hasPermissionTo('applications.view'))
            <a href="{{ route('admin.applications.index') }}" class="rounded-xl border border-[#DCE9DF] bg-white p-5 transition hover:border-[#08713D] hover:shadow-sm">
                <h4 class="font-bold text-[#182A22]">{{ __('Membership applications') }}</h4>
                <p class="mt-2 text-sm leading-6 text-[#617067]">{{ __('Review and process new member applications.') }}</p>
            </a>
        @endif
        @if ($administrator->hasPermissionTo('members.view'))
            <a href="{{ route('admin.members.index') }}" class="rounded-xl border border-[#DCE9DF] bg-white p-5 transition hover:border-[#08713D] hover:shadow-sm">
                <h4 class="font-bold text-[#182A22]">{{ __('Members') }}</h4>
                <p class="mt-2 text-sm leading-6 text-[#617067]">{{ __('View member records and account status.') }}</p>
            </a>
        @endif
        @if ($administrator->hasPermissionTo('notices.manage'))
            <a href="{{ route('admin.notices.index') }}" class="rounded-xl border border-[#DCE9DF] bg-white p-5 transition hover:border-[#08713D] hover:shadow-sm">
                <h4 class="font-bold text-[#182A22]">{{ __('Notices') }}</h4>
                <p class="mt-2 text-sm leading-6 text-[#617067]">{{ __('Create, publish, and update website announcements.') }}</p>
            </a>
        @endif
        @if ($administrator->hasPermissionTo('contact_inquiries.manage'))
            <a href="{{ route('admin.contact-inquiries.index') }}" class="rounded-xl border border-[#DCE9DF] bg-white p-5 transition hover:border-[#08713D] hover:shadow-sm">
                <h4 class="font-bold text-[#182A22]">{{ __('Contact inquiries') }}</h4>
                <p class="mt-2 text-sm leading-6 text-[#617067]">{{ __('Respond to questions from cooperative visitors.') }}</p>
            </a>
        @endif
        @if ($administrator->hasPermissionTo('staff.manage'))
            <a href="{{ route('admin.staff.index') }}" class="rounded-xl border border-[#DCE9DF] bg-white p-5 transition hover:border-[#08713D] hover:shadow-sm">
                <h4 class="font-bold text-[#182A22]">{{ __('Staff & permissions') }}</h4>
                <p class="mt-2 text-sm leading-6 text-[#617067]">{{ __('Manage staff accounts and their access.') }}</p>
            </a>
        @endif
    </div>
</section>

<aside class="rounded-xl border border-[#E8DDA6] bg-[#FFFBE5] p-5">
    <h3 class="font-bold text-[#182A22]">{{ __('Financial data') }}</h3>
    <p class="mt-2 text-sm leading-6 text-[#5F5A3D]">{{ __('No financial totals are shown until verified records and authorized workflows are available.') }}</p>
</aside>
@endsection
