@extends('layouts.admin')

@section('title', __('Membership applications'))
@section('page-title', __('Membership applications'))

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-[#182A22]">Membership applications</h2>
        <p class="mt-1 text-sm text-[#617067]">Review applicants and track their application status.</p>
    </div>
    <span class="rounded-full bg-[#E7F4EB] px-4 py-2 text-sm font-semibold text-[#04552F]">{{ $applications->total() }} total</span>
</div>

<form method="GET" action="{{ route('admin.applications.index') }}" class="mb-5 grid gap-3 rounded-2xl border border-[#DCE9DF] bg-white p-4 sm:grid-cols-[1fr_220px_auto]">
    <label class="sr-only" for="search">Search applications</label>
    <input id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, email, or phone" class="rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-2.5 text-sm text-[#182A22]">
    <label class="sr-only" for="status">Filter by status</label>
    <select id="status" name="status" class="rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm text-[#182A22]">
        <option value="">All statuses</option>
        @foreach (['pending', 'under_review', 'additional_documents_required', 'approved', 'rejected'] as $status)
            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
        @endforeach
    </select>
    <button class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">Filter</button>
</form>

<div class="overflow-hidden rounded-2xl border border-[#DCE9DF] bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-[#E8EFEA] text-left text-sm">
            <thead class="bg-[#F5F8F6] text-xs uppercase tracking-wide text-[#617067]">
                <tr><th class="px-5 py-3">Applicant</th><th class="px-5 py-3">Phone</th><th class="px-5 py-3">Submitted</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Documents</th><th class="px-5 py-3"><span class="sr-only">View</span></th></tr>
            </thead>
            <tbody class="divide-y divide-[#E8EFEA]">
                @forelse ($applications as $application)
                    <tr>
                        <td class="px-5 py-4"><a href="{{ route('admin.applications.show', $application) }}" class="font-semibold text-[#08713D] hover:underline">{{ $application->user->name }}</a><p class="mt-1 text-xs text-[#617067]">{{ $application->user->email }}</p></td>
                        <td class="whitespace-nowrap px-5 py-4 text-[#344054]">{{ $application->phone }}</td>
                        <td class="whitespace-nowrap px-5 py-4 text-[#344054]">{{ $application->submitted_at->format('M j, Y') }}</td>
                        <td class="px-5 py-4"><span class="rounded-full bg-[#F0F4F1] px-3 py-1 text-xs font-semibold text-[#344054]">{{ str_replace('_', ' ', ucfirst($application->status)) }}</span></td>
                        <td class="px-5 py-4 text-[#344054]">{{ $application->documents_count }}</td>
                        <td class="whitespace-nowrap px-5 py-4"><a href="{{ route('admin.applications.show', $application) }}" class="font-semibold text-[#08713D] hover:underline">Review <span aria-hidden="true">→</span></a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-[#617067]">No membership applications match these filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($applications->hasPages())
        <div class="border-t border-[#E8EFEA] px-5 py-4">{{ $applications->links() }}</div>
    @endif
</div>
@endsection
