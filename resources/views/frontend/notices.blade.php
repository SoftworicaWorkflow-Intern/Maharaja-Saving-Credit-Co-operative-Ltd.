@extends('layouts.app')

@section('title', __('Notices'))

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#08713D]">{{ __('Notices') }}</p>
            <h1 class="mt-3 text-4xl font-bold text-[#182A22]">{{ __('Latest announcements') }}</h1>
        </div>
        <form method="GET" class="flex flex-col gap-3 sm:flex-row">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search notices') }}" class="rounded-full border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
            <select name="category" class="rounded-full border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#08713D]/20">
                <option value="">{{ __('All categories') }}</option>
                <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>{{ __('General') }}</option>
                <option value="savings" {{ request('category') == 'savings' ? 'selected' : '' }}>{{ __('Savings') }}</option>
                <option value="loan" {{ request('category') == 'loan' ? 'selected' : '' }}>{{ __('Loan') }}</option>
            </select>
            <button type="submit" class="rounded-full bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white">{{ __('Filter') }}</button>
        </form>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        @forelse($notices as $notice)
            <article class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <span class="rounded-full bg-[#EFF8F1] px-3 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-[#08713D]">{{ __(ucfirst($notice->category)) }}</span>
                    @if($notice->featured)
                        <span class="rounded-full bg-[#FFD91A] px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-[#04552F]">{{ __('Featured') }}</span>
                    @endif
                </div>
                <h2 class="mt-4 text-xl font-semibold text-[#182A22]"><a lang="{{ app()->getLocale() }}" href="{{ route('notices.show', $notice) }}" class="hover:text-[#08713D] hover:underline">{{ app()->getLocale() === 'ne' ? ($notice->title_np ?: $notice->title_en) : ($notice->title_en ?: $notice->title_np) }}</a></h2>
                <p lang="{{ app()->getLocale() }}" class="mt-3 text-sm text-[#617067]">{{ Str::limit(strip_tags(app()->getLocale() === 'ne' ? ($notice->body_np ?: $notice->body_en) : ($notice->body_en ?: $notice->body_np)), 150) }}</p>
                <div class="mt-5 text-xs font-medium uppercase tracking-[0.12em] text-[#617067]">{{ $notice->published_at?->format('M d, Y') }}</div>
            </article>
        @empty
            <div class="lg:col-span-3 rounded-3xl border border-dashed border-[#DCE9DF] bg-white p-10 text-center text-[#617067]">
                {{ __('No notices found.') }}
            </div>
        @endforelse
    </div>

    @if($notices->hasPages())
        <div class="mt-10">{{ $notices->links() }}</div>
    @endif
</section>
@endsection
