@extends('layouts.admin')

@section('title', __('Notices'))
@section('page-title', __('Notices'))

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-[#182A22]">Notices & announcements</h2>
        <p class="mt-1 text-sm text-[#617067]">Create and publish bilingual notices for the website news ticker.</p>
    </div>
    <a href="{{ route('admin.notices.create') }}" class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">Create notice</a>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#04552F]">{{ session('status') }}</div>
@endif

<form method="GET" action="{{ route('admin.notices.index') }}" class="mb-5 grid gap-3 rounded-xl border border-[#DCE9DF] bg-white p-4 sm:grid-cols-[1fr_220px_auto]">
    <label for="notice-search" class="sr-only">Search notice titles</label>
    <input id="notice-search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search English or Nepali title" class="rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-2.5 text-sm text-[#182A22]">
    <label for="notice-status" class="sr-only">Filter by publication status</label>
    <select id="notice-status" name="status" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm text-[#182A22]">
        <option value="">All notices</option>
        <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Draft</option>
        <option value="published" @selected(($filters['status'] ?? '') === 'published')>Published</option>
    </select>
    <button type="submit" class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">Filter</button>
</form>

<div class="overflow-hidden rounded-xl border border-[#DCE9DF] bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-[#E8EFEA] text-left text-sm">
            <thead class="bg-[#F5F8F6] text-xs uppercase tracking-wide text-[#617067]">
                <tr><th class="px-5 py-3">Notice</th><th class="px-5 py-3">Category</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Published</th><th class="px-5 py-3"><span class="sr-only">Actions</span></th></tr>
            </thead>
            <tbody class="divide-y divide-[#E8EFEA]">
                @forelse ($notices as $notice)
                    <tr>
                        <td class="min-w-64 px-5 py-4">
                            <a href="{{ route('admin.notices.edit', $notice) }}" class="font-semibold text-[#08713D] hover:underline">{{ $notice->title_en }}</a>
                            <p lang="ne" class="mt-1 text-xs text-[#617067]">{{ $notice->title_np }}</p>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 capitalize text-[#344054]">{{ $notice->category }}</td>
                        <td class="px-5 py-4"><span @class(['rounded-full px-3 py-1 text-xs font-semibold', 'bg-amber-100 text-amber-800' => $notice->status === 'draft', 'bg-[#E7F4EB] text-[#04552F]' => $notice->status === 'published'])>{{ ucfirst($notice->status) }}</span></td>
                        <td class="whitespace-nowrap px-5 py-4 text-[#344054]">{{ $notice->published_at?->format('M j, Y g:i A') ?? '—' }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-right">
                            <a href="{{ route('admin.notices.edit', $notice) }}" class="font-semibold text-[#08713D] hover:underline">Edit</a>
                            @if ($notice->status === 'published' && (! $notice->published_at || $notice->published_at->isPast()))
                                <a href="{{ route('notices.show', $notice) }}" class="ml-3 font-semibold text-[#08713D] hover:underline">View</a>
                            @endif
                            <form method="POST" action="{{ route('admin.notices.destroy', $notice) }}" class="ml-3 inline" onsubmit="return confirm('Delete this notice? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-semibold text-red-700 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-[#617067]">No notices match these filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($notices->hasPages())
        <div class="border-t border-[#E8EFEA] px-5 py-4">{{ $notices->links() }}</div>
    @endif
</div>
@endsection
