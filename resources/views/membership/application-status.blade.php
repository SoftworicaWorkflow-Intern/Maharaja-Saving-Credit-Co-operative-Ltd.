@extends('layouts.app')

@section('title', 'Membership application')

@section('content')
<section class="mx-auto max-w-4xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-[#DCE9DF] bg-white p-6 shadow-sm sm:p-10">
        <p class="text-sm font-semibold uppercase tracking-[0.15em] text-[#08713D]">Membership application</p>
        <h1 class="mt-3 text-3xl font-extrabold text-[#182A22]">Your application status</h1>

        @if (session('status'))
            <div role="status" class="mt-6 rounded-2xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#04552F]">{{ session('status') }}</div>
        @endif

        @if ($application)
            @php
                $statusLabels = [
                    'pending' => 'Submitted and awaiting review',
                    'under_review' => 'Under review',
                    'additional_documents_required' => 'Additional documents requested',
                    'approved' => 'Approved',
                    'rejected' => 'Application declined',
                ];
            @endphp
            <div class="mt-7 rounded-2xl bg-[#F5F8F6] p-5">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-[#617067]">Current status</dt>
                        <dd class="mt-1 text-base font-bold text-[#182A22]">{{ $statusLabels[$application->status] ?? $application->status }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-[#617067]">Submitted</dt>
                        <dd class="mt-1 text-base font-semibold text-[#182A22]">{{ $application->submitted_at->format('M j, Y') }}</dd>
                    </div>
                </dl>
                @if ($application->review_reason)
                    <div class="mt-5 border-t border-[#DCE9DF] pt-4">
                        <h2 class="text-sm font-semibold text-[#182A22]">Staff note</h2>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-[#617067]">{{ $application->review_reason }}</p>
                    </div>
                @endif
            </div>

            @if ($application->status === \App\Models\MembershipApplication::DOCUMENTS_REQUIRED)
                <div class="mt-7">
                    <h2 class="text-lg font-bold text-[#182A22]">Submit additional documents</h2>
                    <p class="mt-1 text-sm text-[#617067]">Upload PDF, JPG, JPEG, or PNG files. Each file must be 5 MB or smaller.</p>
                    <form method="POST" action="{{ route('membership.application.documents.store') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf
                        <label for="documents" class="block text-sm font-medium text-[#182A22]">Documents (up to 5 files)</label>
                        <input id="documents" type="file" name="documents[]" accept=".pdf,.jpg,.jpeg,.png" multiple required class="w-full rounded-xl border border-[#DCE9DF] bg-[#F9FBFA] px-4 py-3 text-sm text-[#182A22]">
                        @error('documents') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        @error('documents.*') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        <button type="submit" class="rounded-full bg-[#08713D] px-6 py-3 text-sm font-semibold text-white hover:bg-[#04552F]">Submit documents</button>
                    </form>
                </div>
            @endif
        @else
            <p class="mt-6 text-sm leading-6 text-[#617067]">No membership application is associated with this account. Contact the cooperative for assistance.</p>
        @endif

        <form method="POST" action="{{ route('logout') }}" class="mt-8 border-t border-[#DCE9DF] pt-5">
            @csrf
            <button type="submit" class="text-sm font-semibold text-[#08713D] hover:underline">Logout</button>
        </form>
    </div>
</section>
@endsection
