@extends('layouts.admin')

@section('title', __('Contact inquiry'))
@section('page-title', __('Contact inquiry'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.contact-inquiries.index') }}" class="text-sm font-semibold text-[#08713D] hover:underline">← All contact inquiries</a>
    <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">{{ $inquiry->subject ?: 'Contact inquiry' }}</h2>
    <p class="mt-1 text-sm text-[#617067]">Received {{ $inquiry->created_at->format('M j, Y g:i A') }}</p>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#04552F]">{{ session('status') }}</div>
@endif

<section class="max-w-3xl rounded-2xl border border-[#DCE9DF] bg-white p-6 sm:p-8">
    <h3 class="text-base font-bold text-[#182A22]">Sender information</h3>
    <dl class="mt-4 grid gap-4 sm:grid-cols-2">
        <div><dt class="text-xs font-semibold uppercase tracking-wide text-[#617067]">Name</dt><dd class="mt-1 text-sm font-medium text-[#182A22]">{{ $inquiry->name }}</dd></div>
        <div><dt class="text-xs font-semibold uppercase tracking-wide text-[#617067]">Email</dt><dd class="mt-1 break-all text-sm font-medium text-[#182A22]"><a class="text-[#08713D] hover:underline" href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></dd></div>
        @if ($inquiry->phone)
            <div><dt class="text-xs font-semibold uppercase tracking-wide text-[#617067]">Phone</dt><dd class="mt-1 text-sm font-medium text-[#182A22]"><a class="text-[#08713D] hover:underline" href="tel:{{ $inquiry->phone }}">{{ $inquiry->phone }}</a></dd></div>
        @endif
    </dl>

    <div class="mt-6 border-t border-[#E8EFEA] pt-5">
        <h3 class="text-sm font-bold text-[#182A22]">Message</h3>
        <p class="mt-3 whitespace-pre-wrap break-words text-sm leading-7 text-[#344054]">{{ $inquiry->message }}</p>
    </div>

    <div class="mt-6 border-t border-[#E8EFEA] pt-5">
        @if ($inquiry->reviewed_at)
            <p class="text-sm font-medium text-[#04552F]">Reviewed by {{ $inquiry->reviewer?->name ?? 'a staff member' }} on {{ $inquiry->reviewed_at->format('M j, Y g:i A') }}.</p>
        @else
            <form method="POST" action="{{ route('admin.contact-inquiries.reviewed', $inquiry) }}">
                @csrf
                @method('PATCH')
                <button class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">Mark as reviewed</button>
            </form>
        @endif
    </div>
</section>
@endsection
