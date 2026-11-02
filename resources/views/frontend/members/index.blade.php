@extends('layouts.app')

@section('title', __('Member Directory').' | Maharaj Bachat')
@section('meta-description', __('Browse the approved public directory of active Maharaj Bachat members.'))

@section('content')
<main class="min-h-screen bg-[#F5F8F5] px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
    <div class="mx-auto max-w-5xl">
        <nav aria-label="{{ __('Breadcrumb') }}" class="mb-5 text-sm text-[#617067]">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="font-semibold text-[#14532D] hover:underline">{{ __('Home') }}</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page">{{ __('Member Directory') }}</li>
            </ol>
        </nav>

        <section class="overflow-hidden rounded-2xl border border-[#DCE9DF] bg-white shadow-lg shadow-[#14532D]/10" aria-labelledby="member-directory-heading">
            <div class="flex flex-col gap-4 bg-gradient-to-r from-[#14532D] to-[#27804A] px-5 py-5 text-white sm:flex-row sm:items-center sm:justify-between sm:px-7">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-white/15 text-[#FACC15]" aria-hidden="true">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="4" width="18" height="16" rx="2"/>
                            <circle cx="9" cy="10" r="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 16c.6-1.3 1.6-2 3-2s2.4.7 3 2m3-6h3m-3 4h3"/>
                        </svg>
                    </span>
                    <div>
                        <h1 id="member-directory-heading" class="text-xl font-extrabold">{{ __('Member Directory') }}</h1>
                        <p class="mt-1 text-sm text-white/80">{{ __('Showing registered members of Maharaj Bachat tatha Rin Sahakari Sanstha Ltd.') }}</p>
                    </div>
                </div>
                <a href="{{ route('login') }}" class="inline-flex min-h-10 items-center justify-center gap-2 self-start rounded-lg bg-[#FACC15] px-4 py-2 text-sm font-bold text-[#14532D] transition hover:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-white sm:self-auto">
                    <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5m5 5H3"/>
                    </svg>
                    {{ __('Member Login for Full Access') }}
                </a>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#E4E9EF] bg-[#F8FAFC] px-4 py-3 text-xs text-[#617067] sm:px-6">
                <p>{{ __('Showing :shown of :total members', [
                    'shown' => $members->count(),
                    'total' => number_format($members->total()),
                ]) }}</p>
                <a href="{{ route('register') }}" class="font-bold text-[#14532D] hover:underline">{{ __('Become a Member') }} <span aria-hidden="true">→</span></a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[38rem] border-collapse text-left text-xs">
                    <thead class="bg-white text-[10px] font-bold uppercase tracking-wide text-[#344054]">
                        <tr class="border-b border-[#E4E9EF]">
                            <th scope="col" class="w-14 px-3 py-3 sm:px-4">{{ __('S.N') }}</th>
                            <th scope="col" class="px-3 py-3 sm:px-4">{{ __('Name') }}</th>
                            <th scope="col" class="px-3 py-3 sm:px-4">{{ __('Code') }}</th>
                            <th scope="col" class="px-3 py-3 sm:px-4">{{ __('Share Number') }}</th>
                            <th scope="col" class="w-16 px-3 py-3 text-center sm:px-4">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E4E9EF]">
                        @forelse ($members as $index => $member)
                            <tr data-member-directory-row class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-[#F8FAFC]' }} transition-colors hover:bg-[#F0FBF3]">
                                <td class="px-3 py-2.5 sm:px-4">
                                    <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-md bg-[#F1F5F9] px-1.5 font-bold text-[#617067]">{{ $members->firstItem() + $index }}</span>
                                </td>
                                <td class="px-3 py-2.5 sm:px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-md border border-[#B9DCC4] bg-[#EFF8F1] text-[10px] font-bold text-[#14532D]" aria-hidden="true">MB</span>
                                        <span class="font-bold text-[#182A22]">{{ $member->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-2.5 sm:px-4">
                                    <span data-member-number-value class="inline-flex rounded-full border border-[#B9DCC4] bg-[#F0FBF3] px-2 py-1 font-mono text-[10px] text-[#27804A]" aria-label="{{ __('Hidden for privacy') }}">••••••••</span>
                                </td>
                                <td class="px-3 py-2.5 sm:px-4">
                                    <span class="inline-flex rounded-full border border-[#F3E28C] bg-[#FFF9D7] px-2 py-1 font-mono text-[10px] text-[#9A7600]" aria-label="{{ __('Hidden for privacy') }}">••••</span>
                                </td>
                                <td class="px-3 py-2.5 text-center sm:px-4">
                                    <button type="button" data-member-number-toggle data-member-number-url="{{ route('members.number', $member->id) }}" data-hidden-label="{{ __('Hidden for privacy') }}" data-show-label="{{ __('Show member number') }}" data-hide-label="{{ __('Hide member number') }}" data-unavailable-label="{{ __('Not available') }}" aria-label="{{ __('Show member number') }}" aria-pressed="false" class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-[#B9DCC4] bg-[#F0FBF3] text-[#14532D] transition hover:bg-[#DDF3E4] focus:outline-none focus:ring-2 focus:ring-[#27804A] focus:ring-offset-1">
                                        <svg data-member-eye-closed aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.7 10.7 0 0 1 12 5c6.5 0 10 7 10 7a15.7 15.7 0 0 1-3.1 3.8M6.2 6.2C3.5 8 2 12 2 12s3.5 7 10 7a10.7 10.7 0 0 0 3-.4"/>
                                        </svg>
                                        <svg data-member-eye-open aria-hidden="true" class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/>
                                            <circle cx="12" cy="12" r="2.5"/>
                                        </svg>
                                    </button>
                                    <span data-member-number-error role="alert" class="hidden text-[10px] text-red-700">{{ __('Unable to load member number. Please try again.') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-sm text-[#617067]">{{ __('No registered members to preview.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($members->hasPages())
                <nav aria-label="{{ __('Member directory pages') }}" class="border-t border-[#E4E9EF] px-4 py-4 sm:px-6">
                    {{ $members->links() }}
                </nav>
            @endif

            <div class="border-t border-[#E4E9EF] bg-[#F8FAFC] px-4 py-3 text-xs text-[#617067] sm:px-6">
                {{ __('Member account details stay private') }}. {{ __('Sign in to access your secure member account.') }}
            </div>
        </section>
    </div>
</main>
@endsection
