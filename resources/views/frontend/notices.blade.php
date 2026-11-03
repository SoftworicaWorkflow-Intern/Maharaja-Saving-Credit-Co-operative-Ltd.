@extends('layouts.app')

@section('title', __('News & Notices'))

                            @section('content')
                            <section class="relative isolate overflow-hidden bg-gradient-to-br from-[#02391F] via-[#04552F] to-[#08713D] text-white">
                                <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-40" style="background-image: repeating-linear-gradient(135deg, transparent 0 25px, rgba(255,255,255,0.07) 25px 26px);"></div>
                                <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
                                    <p lang="ne" class="text-sm font-semibold text-[#FFD91A]">समाचार तथा सूचनाहरू</p>
                                    <h1 class="mt-3 text-4xl font-extrabold leading-tight sm:text-5xl">News &amp; <span class="text-[#FFD91A]">Notices</span></h1>
                                    <nav class="mt-5" aria-label="{{ __('Breadcrumb') }}">
                                        <ol class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide">
                                            <li><a href="{{ route('home') }}" class="text-white/75 hover:text-white">{{ __('Home') }}</a></li>
                                            <li aria-hidden="true" class="text-white/50">&gt;</li>
                                            <li class="text-[#FFD91A]" aria-current="page">News &amp; Notices</li>
                                        </ol>
                                    </nav>
                                </div>
                            </section>

                            <section class="fade-in bg-[#F7FAF7]">
                                <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                                    <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                                        <p class="max-w-2xl text-sm leading-6 text-[#617067]">{{ __('Official announcements, service updates, and important information for our members.') }}</p>
                                        <form method="GET" action="{{ route('notices') }}" role="search" aria-label="{{ __('Search and filter notices') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[minmax(13rem,1fr)_12rem_11rem_auto]">
                                            <label class="sr-only" for="notice-search">{{ __('Search notices') }}</label>
                                            <input id="notice-search" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('Search title or keyword') }}" class="min-w-0 rounded-lg border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/30">
                                            <label class="sr-only" for="notice-category">{{ __('Filter by category') }}</label>
                                            <select id="notice-category" name="category" class="rounded-lg border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/30">
                                                <option value="">{{ __('All categories') }}</option>
                                                @foreach (\App\Models\Notice::CATEGORIES as $category)
                                                    <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ __(ucfirst($category)) }}</option>
                                                @endforeach
                                            </select>
                                            <label class="sr-only" for="notice-published-on">{{ __('Filter by publication date') }}</label>
                                            <input id="notice-published-on" type="date" name="published_on" value="{{ $filters['published_on'] ?? '' }}" aria-label="{{ __('Filter by publication date') }}" class="rounded-lg border border-[#DCE9DF] bg-white px-4 py-3 text-sm text-[#182A22] focus:outline-none focus:ring-2 focus:ring-[#08713D]/30">
                                            <button type="submit" class="rounded-lg bg-[#08713D] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#04552F] focus:outline-none focus:ring-2 focus:ring-[#08713D] focus:ring-offset-2">{{ __('Search') }}</button>
                                        </form>
                                    </div>

                                    @if (request()->hasAny(['search', 'category', 'published_on']))
                                        <a href="{{ route('notices') }}" class="mb-6 inline-flex text-sm font-semibold text-[#08713D] hover:underline">{{ __('Clear filters') }}</a>
                                    @endif

                                    @if ($notices->isNotEmpty())
                                        <div class="grid auto-rows-fr gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                            @foreach ($notices as $notice)
                                                @php
                                                    $isNepali = app()->getLocale() === 'ne';
                                                    $noticeTitle = $isNepali ? ($notice->title_np ?: $notice->title_en) : ($notice->title_en ?: $notice->title_np);
                                                    $noticeBody = $isNepali ? ($notice->body_np ?: $notice->body_en) : ($notice->body_en ?: $notice->body_np);
                                                @endphp
                                                <article class="flex h-full flex-col overflow-hidden rounded-xl border border-[#DCE9DF] bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                                                    <div data-notice-preview class="relative flex aspect-[1.55] items-center justify-center overflow-hidden bg-[#EFF8F1] p-3">
                                                        @if ($notice->document_path && str_starts_with($notice->document_mime_type ?? '', 'image/'))
                                                            <img src="{{ route('notices.document', $notice) }}" alt="{{ __('Notice document') }}: {{ $noticeTitle }}" loading="lazy" class="h-full w-full object-contain">
                                                        @elseif ($notice->document_path && $notice->document_mime_type === 'application/pdf')
                                                            <div class="flex h-full w-full flex-col items-center justify-center gap-3 rounded-lg border border-[#DCE9DF] bg-white text-[#04552F]">
                                                                <svg aria-hidden="true" class="h-12 w-12" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linejoin="round" d="M13 4h15l9 9v31H13z"/><path stroke-linejoin="round" d="M28 4v10h9M18 25h14M18 31h14M18 37h9"/></svg>
                                                                <span class="rounded bg-[#EFF8F1] px-2 py-1 text-xs font-bold tracking-wide">PDF</span>
                                                            </div>
                                                        @else
                                                            <div class="flex h-full w-full flex-col items-center justify-center gap-3 rounded-lg border border-dashed border-[#B6D8C0] bg-white text-[#08713D]">
                                                                <svg aria-hidden="true" class="h-10 w-10" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linejoin="round" d="M13 4h15l9 9v31H13z"/><path stroke-linejoin="round" d="M28 4v10h9M18 25h14M18 31h14"/></svg>
                                                                <span class="text-xs font-semibold">{{ __('Notice document') }}</span>
                                                            </div>
                                                        @endif
                                                        <span class="absolute left-5 top-5 rounded bg-[#FFD91A] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-[#04552F]">{{ __(ucfirst($notice->category)) }}</span>
                                                        @if ($notice->featured)
                                                            <span class="absolute right-5 top-5 rounded bg-[#04552F] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white">{{ __('Important') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="flex flex-1 flex-col p-5">
                                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs font-medium text-[#617067]">
                                                            @if ($notice->published_at)
                                                                <span class="inline-flex items-center gap-1.5">
                                                                    <svg aria-hidden="true" class="h-4 w-4 text-[#08713D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M16 3v4M8 3v4M3 10h18"/></svg>
                                                                    <time datetime="{{ $notice->published_at->toIso8601String() }}">{{ $notice->published_at->translatedFormat('M j, Y') }}</time>
                                                                </span>
                                                            @endif
                                                            <span class="inline-flex items-center gap-1.5">
                                                                <svg aria-hidden="true" class="h-4 w-4 text-[#08713D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m20 13-7 7-10-10V3h7l10 10Z"/><circle cx="7.5" cy="7.5" r="1"/></svg>
                                                                {{ __(ucfirst($notice->category)) }}
                                                            </span>
                                                        </div>
                                                        <h2 class="mt-4 line-clamp-2 text-lg font-bold leading-snug text-[#182A22]">
                                                            <a lang="{{ $isNepali ? 'ne' : 'en' }}" href="{{ route('notices.show', $notice) }}" class="hover:text-[#08713D] hover:underline">{{ $noticeTitle }}</a>
                                                        </h2>
                                                        @if ($noticeBody)
                                                            <p lang="{{ $isNepali ? 'ne' : 'en' }}" class="mt-2 line-clamp-3 text-sm leading-6 text-[#617067]">{{ Str::limit(strip_tags($noticeBody), 160) }}</p>
                                                        @endif
                                                        <a href="{{ route('notices.show', $notice) }}" aria-label="{{ __('Read full notice') }}: {{ $noticeTitle }}" class="mt-auto inline-flex items-center gap-2 pt-5 text-xs font-bold uppercase tracking-wide text-[#08713D] hover:text-[#04552F]">
                                                            {{ __('Read full notice') }}
                                                            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                                                        </a>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="rounded-xl border border-dashed border-[#B6D8C0] bg-white px-6 py-14 text-center" role="status">
                                            <h2 class="text-xl font-bold text-[#182A22]">{{ __('No notices found') }}</h2>
                                            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#617067]">{{ __('Try another search term or clear your filters to see all published notices.') }}</p>
                                            @if (request()->hasAny(['search', 'category', 'published_on']))
                                                <a href="{{ route('notices') }}" class="mt-5 inline-flex rounded-lg bg-[#08713D] px-5 py-3 text-sm font-semibold text-white hover:bg-[#04552F]">{{ __('Clear filters') }}</a>
                                            @endif
                                        </div>
                                    @endif

                                    @if (method_exists($notices, 'hasPages') && $notices->hasPages())
                                        <div class="mt-10">{{ $notices->links() }}</div>
                                    @endif
                                </div>
                            </section>
                            @endsection
