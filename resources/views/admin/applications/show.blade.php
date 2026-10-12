@extends('layouts.admin')

@section('title', __('Application review'))
@section('page-title', __('Application review'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.applications.index') }}" class="text-sm font-semibold text-[#08713D] hover:underline">← All applications</a>
    <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">{{ $application->user->name }}</h2>
    <p class="mt-1 text-sm text-[#617067]">Application #{{ $application->id }} · {{ str_replace('_', ' ', ucfirst($application->status)) }}</p>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#04552F]">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $errors->first() }}</div>
@endif

<div class="grid gap-5 lg:grid-cols-[1fr_0.85fr]">
    <section class="rounded-2xl border border-[#DCE9DF] bg-white p-6">
        <h3 class="text-base font-bold text-[#182A22]">Applicant information</h3>
        <dl class="mt-5 grid gap-4 sm:grid-cols-2">
            <div><dt class="text-xs font-semibold uppercase text-[#617067]">Email</dt><dd class="mt-1 break-all text-sm font-medium text-[#182A22]">{{ $application->user->email }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-[#617067]">Phone</dt><dd class="mt-1 text-sm font-medium text-[#182A22]">{{ $application->phone }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase text-[#617067]">Address</dt><dd class="mt-1 whitespace-pre-line text-sm font-medium text-[#182A22]">{{ $application->address }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-[#617067]">Submitted</dt><dd class="mt-1 text-sm font-medium text-[#182A22]">{{ $application->submitted_at->format('M j, Y g:i A') }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-[#617067]">Last reviewed by</dt><dd class="mt-1 text-sm font-medium text-[#182A22]">{{ $application->reviewer?->name ?? 'Not reviewed' }}</dd></div>
        </dl>
    </section>

    <section class="rounded-2xl border border-[#DCE9DF] bg-white p-6">
        <h3 class="text-base font-bold text-[#182A22]">Private documents</h3>
        @forelse ($application->documents as $document)
            <div class="mt-3 flex items-center justify-between gap-3 rounded-xl bg-[#F5F8F6] p-3">
                <div class="min-w-0"><p class="truncate text-sm font-semibold text-[#182A22]">{{ basename($document->path) }}</p><p class="mt-1 text-xs text-[#617067]">Uploaded by {{ $document->uploader?->name ?? 'Unknown' }}</p></div>
                <a href="{{ route('admin.applications.documents.download', $document) }}" class="shrink-0 text-sm font-semibold text-[#08713D] hover:underline">Download</a>
            </div>
        @empty
            <p class="mt-3 text-sm text-[#617067]">No documents have been submitted.</p>
        @endforelse
    </section>
</div>

@if (in_array($application->status, \App\Models\MembershipApplication::OPEN_STATUSES, true))
    <section class="mt-5 rounded-2xl border border-[#DCE9DF] bg-white p-6">
        <h3 class="text-base font-bold text-[#182A22]">Record a review decision</h3>
        <form method="POST" action="{{ route('admin.applications.review', [$application, 'start_review']) }}" class="mt-4 grid gap-3 sm:grid-cols-[1fr_auto]">
            @csrf
            <input type="text" name="note" maxlength="2000" placeholder="Optional review note" class="rounded-xl border border-[#DCE9DF] px-4 py-2.5 text-sm text-[#182A22]">
            <button class="rounded-xl border border-[#B6D8C0] px-5 py-2.5 text-sm font-semibold text-[#04552F] hover:bg-[#E7F4EB]">Start review</button>
        </form>
        <form method="POST" action="{{ route('admin.applications.review', [$application, 'request_documents']) }}" class="mt-3 grid gap-3 sm:grid-cols-[1fr_auto]">
            @csrf
            <input type="text" name="note" required maxlength="2000" placeholder="Explain which documents are needed" class="rounded-xl border border-[#DCE9DF] px-4 py-2.5 text-sm text-[#182A22]">
            <button class="rounded-xl border border-amber-300 px-5 py-2.5 text-sm font-semibold text-amber-800 hover:bg-amber-50">Request documents</button>
        </form>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            <form method="POST" action="{{ route('admin.applications.review', [$application, 'approve']) }}" class="space-y-2">
                @csrf
                <textarea name="note" maxlength="2000" rows="2" placeholder="Optional approval note" class="w-full rounded-xl border border-[#DCE9DF] px-4 py-2.5 text-sm text-[#182A22]"></textarea>
                <button class="w-full rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">Approve application</button>
            </form>
            <form method="POST" action="{{ route('admin.applications.review', [$application, 'reject']) }}" class="space-y-2">
                @csrf
                <textarea name="note" required maxlength="2000" rows="2" placeholder="Reason for rejection (required)" class="w-full rounded-xl border border-[#DCE9DF] px-4 py-2.5 text-sm text-[#182A22]"></textarea>
                <button class="w-full rounded-xl border border-red-200 px-5 py-2.5 text-sm font-semibold text-red-700 hover:bg-red-50">Reject application</button>
            </form>
        </div>
    </section>
@endif

<section class="mt-5 rounded-2xl border border-[#DCE9DF] bg-white p-6">
    <h3 class="text-base font-bold text-[#182A22]">Application history</h3>
    <ol class="mt-4 space-y-4">
        @foreach ($application->events as $event)
            <li class="border-l-2 border-[#B6D8C0] pl-4">
                <p class="text-sm font-semibold text-[#182A22]">{{ str_replace('_', ' ', ucfirst($event->event)) }}</p>
                <p class="mt-1 text-xs text-[#617067]">{{ $event->created_at->format('M j, Y g:i A') }} · {{ $event->actor?->name ?? 'System' }}</p>
                @if ($event->note)<p class="mt-2 whitespace-pre-line text-sm text-[#617067]">{{ $event->note }}</p>@endif
            </li>
        @endforeach
    </ol>
</section>
@endsection
