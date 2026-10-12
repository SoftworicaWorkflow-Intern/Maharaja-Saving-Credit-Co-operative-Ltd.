@extends('layouts.admin')

@section('title', __('Member profile'))
@section('page-title', __('Member profile'))

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.members.index') }}" class="text-sm font-semibold text-[#08713D] hover:underline">← All members</a>
    <h2 class="mt-3 text-2xl font-extrabold text-[#182A22]">{{ $member->user->name }}</h2>
    <p class="mt-1 text-sm text-[#617067]">{{ $member->member_number ?? 'Member number not assigned' }} · {{ ucfirst($member->status) }}</p>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#04552F]">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $errors->first() }}</div>
@endif

<div class="grid gap-5 lg:grid-cols-2">
    <section class="rounded-2xl border border-[#DCE9DF] bg-white p-6">
        <h3 class="text-base font-bold text-[#182A22]">Member details</h3>
        <dl class="mt-5 grid gap-4">
            <div><dt class="text-xs font-semibold uppercase text-[#617067]">Email</dt><dd class="mt-1 break-all text-sm font-medium text-[#182A22]">{{ $member->user->email }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-[#617067]">Joined</dt><dd class="mt-1 text-sm font-medium text-[#182A22]">{{ $member->joined_at->format('M j, Y') }}</dd></div>
        </dl>
        @if (auth()->user()->hasPermissionTo('members.update'))
            <form method="POST" action="{{ route('admin.members.update', $member) }}" class="mt-6 space-y-4 border-t border-[#E8EFEA] pt-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="phone" class="mb-1 block text-sm font-medium text-[#182A22]">Phone</label>
                    <input id="phone" name="phone" value="{{ old('phone', $member->phone) }}" required maxlength="40" class="w-full rounded-xl border border-[#DCE9DF] px-4 py-2.5 text-sm text-[#182A22]">
                </div>
                <div>
                    <label for="address" class="mb-1 block text-sm font-medium text-[#182A22]">Address</label>
                    <textarea id="address" name="address" rows="3" required maxlength="500" class="w-full rounded-xl border border-[#DCE9DF] px-4 py-2.5 text-sm text-[#182A22]">{{ old('address', $member->address) }}</textarea>
                </div>
                <button class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">Save member details</button>
            </form>
        @endif
    </section>

    @if (auth()->user()->hasPermissionTo('members.update'))
        <section class="rounded-2xl border border-[#DCE9DF] bg-white p-6">
            <h3 class="text-base font-bold text-[#182A22]">Membership status</h3>
            <p class="mt-1 text-sm leading-6 text-[#617067]">Suspended members cannot access the member portal. Record a reason for every status change.</p>
            <form method="POST" action="{{ route('admin.members.status', $member) }}" class="mt-5 space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label for="status" class="mb-1 block text-sm font-medium text-[#182A22]">Status</label>
                    <select id="status" name="status" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm text-[#182A22]">
                        <option value="active" @selected(old('status', $member->status) === 'active')>Active</option>
                        <option value="suspended" @selected(old('status', $member->status) === 'suspended')>Suspended</option>
                    </select>
                </div>
                <div>
                    <label for="reason" class="mb-1 block text-sm font-medium text-[#182A22]">Reason</label>
                    <textarea id="reason" name="reason" rows="3" required maxlength="2000" class="w-full rounded-xl border border-[#DCE9DF] px-4 py-2.5 text-sm text-[#182A22]">{{ old('reason') }}</textarea>
                </div>
                <button class="rounded-xl border border-[#B6D8C0] px-5 py-2.5 text-sm font-semibold text-[#04552F] hover:bg-[#E7F4EB]">Update status</button>
            </form>
        </section>
    @endif
</div>

<section class="mt-5 rounded-2xl border border-[#DCE9DF] bg-white p-6">
    <h3 class="text-base font-bold text-[#182A22]">Membership history</h3>
    <ol class="mt-4 space-y-4">
        @foreach ($member->membershipApplication->events as $event)
            <li class="border-l-2 border-[#B6D8C0] pl-4">
                <p class="text-sm font-semibold text-[#182A22]">{{ str_replace('_', ' ', ucfirst($event->event)) }}</p>
                <p class="mt-1 text-xs text-[#617067]">{{ $event->created_at->format('M j, Y g:i A') }} · {{ $event->actor?->name ?? 'System' }}</p>
                @if ($event->note)<p class="mt-2 whitespace-pre-line text-sm text-[#617067]">{{ $event->note }}</p>@endif
            </li>
        @endforeach
    </ol>
</section>
@endsection
