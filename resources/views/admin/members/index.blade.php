@extends('layouts.admin')

@section('title', __('Members'))
@section('page-title', __('Members'))

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-[#182A22]">Members</h2>
        <p class="mt-1 text-sm text-[#617067]">Search approved member records and maintain current contact information.</p>
    </div>
    <span class="rounded-full bg-[#E7F4EB] px-4 py-2 text-sm font-semibold text-[#04552F]">{{ $members->total() }} total</span>
</div>

<form method="GET" action="{{ route('admin.members.index') }}" class="mb-5 grid gap-3 rounded-2xl border border-[#DCE9DF] bg-white p-4 sm:grid-cols-2 lg:grid-cols-5">
    <label class="sr-only" for="search">Search members</label>
    <input id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, email, member number" class="rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-2.5 text-sm text-[#182A22] lg:col-span-2">
    <label class="sr-only" for="status">Status</label>
    <select id="status" name="status" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm text-[#182A22]">
        <option value="">All statuses</option>
        @foreach (['active', 'suspended'] as $status)
            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    <label class="sr-only" for="registered_from">Joined from</label>
    <input id="registered_from" type="date" name="registered_from" value="{{ $filters['registered_from'] ?? '' }}" aria-label="Joined from" class="rounded-xl border border-[#DCE9DF] bg-white px-3 py-2.5 text-sm text-[#182A22]">
    <div class="flex gap-2">
        <label class="sr-only" for="registered_to">Joined through</label>
        <input id="registered_to" type="date" name="registered_to" value="{{ $filters['registered_to'] ?? '' }}" aria-label="Joined through" class="min-w-0 flex-1 rounded-xl border border-[#DCE9DF] bg-white px-3 py-2.5 text-sm text-[#182A22]">
        <button class="rounded-xl bg-[#08713D] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">Filter</button>
    </div>
</form>

<div class="overflow-hidden rounded-2xl border border-[#DCE9DF] bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-[#E8EFEA] text-left text-sm">
            <thead class="bg-[#F5F8F6] text-xs uppercase tracking-wide text-[#617067]">
                <tr><th class="px-5 py-3">Member</th><th class="px-5 py-3">Member number</th><th class="px-5 py-3">Phone</th><th class="px-5 py-3">Joined</th><th class="px-5 py-3">Status</th><th class="px-5 py-3"><span class="sr-only">View</span></th></tr>
            </thead>
            <tbody class="divide-y divide-[#E8EFEA]">
                @forelse ($members as $member)
                    <tr>
                        <td class="px-5 py-4"><a href="{{ route('admin.members.show', $member) }}" class="font-semibold text-[#08713D] hover:underline">{{ $member->user->name }}</a><p class="mt-1 text-xs text-[#617067]">{{ $member->user->email }}</p></td>
                        <td class="whitespace-nowrap px-5 py-4 text-[#344054]">{{ $member->member_number ?? '—' }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-[#344054]">{{ $member->phone }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-[#344054]">{{ $member->joined_at->format('M j, Y') }}</td>
                        <td class="px-5 py-4"><span @class(['rounded-full px-3 py-1 text-xs font-semibold', 'bg-[#E7F4EB] text-[#04552F]' => $member->status === 'active', 'bg-amber-100 text-amber-800' => $member->status !== 'active'])>{{ ucfirst($member->status) }}</span></td>
                        <td class="whitespace-nowrap px-5 py-4"><a href="{{ route('admin.members.show', $member) }}" class="font-semibold text-[#08713D] hover:underline">View <span aria-hidden="true">→</span></a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-[#617067]">No member records match these filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($members->hasPages())
        <div class="border-t border-[#E8EFEA] px-5 py-4">{{ $members->links() }}</div>
    @endif
</div>
@endsection
