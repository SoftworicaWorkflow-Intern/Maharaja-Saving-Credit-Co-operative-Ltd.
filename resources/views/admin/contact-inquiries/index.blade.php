@extends('layouts.admin')

@section('title', __('Contact inquiries'))
@section('page-title', __('Contact inquiries'))

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-[#182A22]">Contact inquiries</h2>
        <p class="mt-1 text-sm text-[#617067]">Review messages submitted through the public contact page.</p>
    </div>
    <span class="rounded-full bg-[#E7F4EB] px-4 py-2 text-sm font-semibold text-[#04552F]">{{ $inquiries->total() }} total</span>
</div>

<form method="GET" action="{{ route('admin.contact-inquiries.index') }}" class="mb-5 grid gap-3 rounded-2xl border border-[#DCE9DF] bg-white p-4 sm:grid-cols-[1fr_220px_auto]">
    <label class="sr-only" for="search">Search contact inquiries</label>
    <input id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, email, subject, or message" class="rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-2.5 text-sm text-[#182A22]">
    <label class="sr-only" for="status">Filter by status</label>
    <select id="status" name="status" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm text-[#182A22]">
        <option value="">All inquiries</option>
        <option value="new" @selected(($filters['status'] ?? '') === 'new')>New</option>
        <option value="reviewed" @selected(($filters['status'] ?? '') === 'reviewed')>Reviewed</option>
    </select>
    <button class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">Filter</button>
</form>

<div class="overflow-hidden rounded-2xl border border-[#DCE9DF] bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-[#E8EFEA] text-left text-sm">
            <thead class="bg-[#F5F8F6] text-xs uppercase tracking-wide text-[#617067]">
                <tr><th class="px-5 py-3">Sender</th><th class="px-5 py-3">Subject</th><th class="px-5 py-3">Received</th><th class="px-5 py-3">Status</th><th class="px-5 py-3"><span class="sr-only">Open</span></th></tr>
            </thead>
            <tbody class="divide-y divide-[#E8EFEA]">
                @forelse ($inquiries as $inquiry)
                    <tr>
                        <td class="px-5 py-4"><a href="{{ route('admin.contact-inquiries.show', $inquiry) }}" class="font-semibold text-[#08713D] hover:underline">{{ $inquiry->name }}</a><p class="mt-1 text-xs text-[#617067]">{{ $inquiry->email }}</p></td>
                        <td class="max-w-sm px-5 py-4"><p class="truncate font-medium text-[#182A22]">{{ $inquiry->subject ?: 'No subject' }}</p><p class="mt-1 truncate text-xs text-[#617067]">{{ $inquiry->message }}</p></td>
                        <td class="whitespace-nowrap px-5 py-4 text-[#344054]">{{ $inquiry->created_at->format('M j, Y g:i A') }}</td>
                        <td class="px-5 py-4"><span @class(['rounded-full px-3 py-1 text-xs font-semibold', 'bg-amber-100 text-amber-800' => $inquiry->reviewed_at === null, 'bg-[#E7F4EB] text-[#04552F]' => $inquiry->reviewed_at !== null])>{{ $inquiry->reviewed_at ? 'Reviewed' : 'New' }}</span></td>
                        <td class="whitespace-nowrap px-5 py-4"><a href="{{ route('admin.contact-inquiries.show', $inquiry) }}" class="font-semibold text-[#08713D] hover:underline">Open <span aria-hidden="true">→</span></a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-[#617067]">No contact inquiries match these filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($inquiries->hasPages())
        <div class="border-t border-[#E8EFEA] px-5 py-4">{{ $inquiries->links() }}</div>
    @endif
</div>
@endsection
