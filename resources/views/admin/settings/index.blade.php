@extends('layouts.admin')

@section('title', __('Settings'))
@section('page-title', __('Settings'))

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-[#182A22]">{{ __('Settings') }}</h2>
    <p class="mt-1 max-w-3xl text-sm leading-6 text-[#617067]">{{ __('Manage administrator and user account passwords.') }}</p>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#04552F]">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $errors->first() }}</div>
@endif

<div class="grid gap-5 xl:grid-cols-2">
    <section class="rounded-2xl border border-[#DCE9DF] bg-white p-6">
        <h3 class="text-base font-bold text-[#182A22]">{{ __('Change administrator password') }}</h3>
        <p class="mt-1 text-sm leading-6 text-[#617067]">{{ __('Confirm your current password before setting a new one.') }}</p>
        <form method="POST" action="{{ route('admin.settings.password.update') }}" class="mt-5 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="current_password" class="mb-1 block text-sm font-medium text-[#182A22]">{{ __('Current password') }}</label>
                <div class="relative">
                    <input id="current_password" name="current_password" type="password" required autocomplete="current-password" class="w-full rounded-xl border border-[#DCE9DF] px-4 py-2.5 pr-12 text-sm text-[#182A22]">
                    <button type="button" data-password-toggle aria-controls="current_password" aria-label="{{ __('Show password') }}" aria-pressed="false" class="absolute inset-y-0 right-1 inline-flex w-10 items-center justify-center rounded-full text-[#617067] transition hover:bg-[#E7F4EB] hover:text-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/30">
                        <svg data-eye-visible aria-hidden="true" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/>
                            <circle cx="12" cy="12" r="2.5"/>
                        </svg>
                        <svg data-eye-hidden aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 6.2A9.8 9.8 0 0 1 12 6c6.1 0 9.5 6 9.5 6a17.7 17.7 0 0 1-3.1 3.8M6.2 6.2C3.9 7.8 2.5 12 2.5 12s3.4 6 9.5 6a9.5 9.5 0 0 0 3-.5"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-medium text-[#182A22]">{{ __('New password') }}</label>
                <div class="relative">
                    <input id="password" name="password" type="password" required minlength="12" autocomplete="new-password" class="w-full rounded-xl border border-[#DCE9DF] px-4 py-2.5 pr-12 text-sm text-[#182A22]">
                    <button type="button" data-password-toggle aria-controls="password" aria-label="{{ __('Show password') }}" aria-pressed="false" class="absolute inset-y-0 right-1 inline-flex w-10 items-center justify-center rounded-full text-[#617067] transition hover:bg-[#E7F4EB] hover:text-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/30">
                        <svg data-eye-visible aria-hidden="true" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                        <svg data-eye-hidden aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 6.2A9.8 9.8 0 0 1 12 6c6.1 0 9.5 6 9.5 6a17.7 17.7 0 0 1-3.1 3.8M6.2 6.2C3.9 7.8 2.5 12 2.5 12s3.4 6 9.5 6a9.5 9.5 0 0 0 3-.5"/></svg>
                    </button>
                </div>
            </div>
            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-medium text-[#182A22]">{{ __('Confirm new password') }}</label>
                <div class="relative">
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="12" autocomplete="new-password" class="w-full rounded-xl border border-[#DCE9DF] px-4 py-2.5 pr-12 text-sm text-[#182A22]">
                    <button type="button" data-password-toggle aria-controls="password_confirmation" aria-label="{{ __('Show password') }}" aria-pressed="false" class="absolute inset-y-0 right-1 inline-flex w-10 items-center justify-center rounded-full text-[#617067] transition hover:bg-[#E7F4EB] hover:text-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/30">
                        <svg data-eye-visible aria-hidden="true" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                        <svg data-eye-hidden aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 6.2A9.8 9.8 0 0 1 12 6c6.1 0 9.5 6 9.5 6a17.7 17.7 0 0 1-3.1 3.8M6.2 6.2C3.9 7.8 2.5 12 2.5 12s3.4 6 9.5 6a9.5 9.5 0 0 0 3-.5"/></svg>
                    </button>
                </div>
            </div>
            <button type="submit" class="rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">{{ __('Update administrator password') }}</button>
        </form>
    </section>

    @if (auth()->user()->hasPermissionTo('staff.manage'))
        <section class="rounded-2xl border border-[#DCE9DF] bg-white p-6">
            <h3 class="text-base font-bold text-[#182A22]">{{ __('Change user password') }}</h3>
            <p class="mt-1 text-sm leading-6 text-[#617067]">{{ __('Set a new password for another account. The selected user will need to use the new password at their next login.') }}</p>
            <form method="POST" action="{{ route('admin.settings.user-password.update') }}" class="mt-5 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="user_id" class="mb-1 block text-sm font-medium text-[#182A22]">{{ __('User account') }}</label>
                    <select id="user_id" name="user_id" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm text-[#182A22]">
                        <option value="">{{ __('Select a user') }}</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>{{ $user->name }} ({{ $user->email }}){{ $user->is_admin ? ' - ' . __('Administrator') : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="user_password" class="mb-1 block text-sm font-medium text-[#182A22]">{{ __('New password') }}</label>
                    <div class="relative">
                        <input id="user_password" name="password" type="password" required minlength="12" autocomplete="new-password" class="w-full rounded-xl border border-[#DCE9DF] px-4 py-2.5 pr-12 text-sm text-[#182A22]">
                        <button type="button" data-password-toggle aria-controls="user_password" aria-label="{{ __('Show password') }}" aria-pressed="false" class="absolute inset-y-0 right-1 inline-flex w-10 items-center justify-center rounded-full text-[#617067] transition hover:bg-[#E7F4EB] hover:text-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/30">
                            <svg data-eye-visible aria-hidden="true" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            <svg data-eye-hidden aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 6.2A9.8 9.8 0 0 1 12 6c6.1 0 9.5 6 9.5 6a17.7 17.7 0 0 1-3.1 3.8M6.2 6.2C3.9 7.8 2.5 12 2.5 12s3.4 6 9.5 6a9.5 9.5 0 0 0 3-.5"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="user_password_confirmation" class="mb-1 block text-sm font-medium text-[#182A22]">{{ __('Confirm new password') }}</label>
                    <div class="relative">
                        <input id="user_password_confirmation" name="password_confirmation" type="password" required minlength="12" autocomplete="new-password" class="w-full rounded-xl border border-[#DCE9DF] px-4 py-2.5 pr-12 text-sm text-[#182A22]">
                        <button type="button" data-password-toggle aria-controls="user_password_confirmation" aria-label="{{ __('Show password') }}" aria-pressed="false" class="absolute inset-y-0 right-1 inline-flex w-10 items-center justify-center rounded-full text-[#617067] transition hover:bg-[#E7F4EB] hover:text-[#08713D] focus:outline-none focus:ring-2 focus:ring-[#08713D]/30">
                            <svg data-eye-visible aria-hidden="true" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            <svg data-eye-hidden aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 6.2A9.8 9.8 0 0 1 12 6c6.1 0 9.5 6 9.5 6a17.7 17.7 0 0 1-3.1 3.8M6.2 6.2C3.9 7.8 2.5 12 2.5 12s3.4 6 9.5 6a9.5 9.5 0 0 0 3-.5"/></svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="rounded-xl border border-[#B6D8C0] px-5 py-2.5 text-sm font-semibold text-[#04552F] hover:bg-[#E7F4EB]">{{ __('Update user password') }}</button>
            </form>
        </section>
    @endif
</div>
@endsection
