@extends('layouts.admin')

@section('title', __('Staff & permissions'))
@section('page-title', __('Staff & permissions'))

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-[#182A22]">Staff & permissions</h2>
    <p class="mt-1 max-w-3xl text-sm leading-6 text-[#617067]">Register a staff account through the public signup form first, then assign its least-privilege role here. A super administrator must grant administration access and staff-management permissions.</p>
</div>

@if (session('status'))
    <div role="status" class="mb-5 rounded-xl border border-[#B6D8C0] bg-[#E7F4EB] px-4 py-3 text-sm font-medium text-[#04552F]">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $errors->first() }}</div>
@endif

<section class="rounded-2xl border border-[#DCE9DF] bg-white p-6">
    <h3 class="text-base font-bold text-[#182A22]">Assign a role to an existing account</h3>
    <form method="POST" action="{{ route('admin.staff.roles.assign') }}" class="mt-4 grid gap-3 sm:grid-cols-[1fr_1fr_auto]">
        @csrf
        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-[#182A22]">Account email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-[#DCE9DF] px-4 py-2.5 text-sm text-[#182A22]">
        </div>
        <div>
            <label for="role" class="mb-1 block text-sm font-medium text-[#182A22]">Role</label>
            <select id="role" name="role" required class="w-full rounded-xl border border-[#DCE9DF] bg-white px-4 py-2.5 text-sm text-[#182A22]">
                <option value="">Select role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->slug }}" @selected(old('role') === $role->slug)>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="self-end rounded-xl bg-[#08713D] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#04552F]">Assign role</button>
    </form>
</section>

<section class="mt-5 rounded-2xl border border-[#DCE9DF] bg-white p-6">
    <h3 class="text-base font-bold text-[#182A22]">Staff accounts</h3>
    <div class="mt-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-[#E8EFEA] text-left text-sm">
            <thead class="bg-[#F5F8F6] text-xs uppercase tracking-wide text-[#617067]">
                <tr><th class="px-4 py-3">Staff member</th><th class="px-4 py-3">Assigned roles</th><th class="px-4 py-3">Effective permissions</th></tr>
            </thead>
            <tbody class="divide-y divide-[#E8EFEA]">
                @forelse ($staff as $user)
                    @php
                        $effectivePermissions = $user->roles
                            ->flatMap(fn ($role) => $role->permissions)
                            ->pluck('slug')
                            ->unique()
                            ->sort()
                            ->values();
                    @endphp
                    <tr>
                        <td class="px-4 py-4"><p class="font-semibold text-[#182A22]">{{ $user->name }}</p><p class="mt-1 text-xs text-[#617067]">{{ $user->email }}</p></td>
                        <td class="px-4 py-4"><div class="flex flex-wrap gap-2">@if ($user->is_admin)<span class="rounded-full bg-[#FFF3BF] px-3 py-1 text-xs font-semibold text-[#6B5000]">Super administrator</span>@endif @foreach ($user->roles as $role)<span class="rounded-full bg-[#E7F4EB] px-3 py-1 text-xs font-semibold text-[#04552F]">{{ $role->name }}</span>@endforeach</div></td>
                        <td class="max-w-xl px-4 py-4"><div class="flex flex-wrap gap-1.5">@forelse ($effectivePermissions as $permission)<span class="rounded bg-[#F0F4F1] px-2 py-1 text-xs text-[#344054]">{{ $permission }}</span>@empty<span class="text-xs text-[#617067]">No permissions assigned</span>@endforelse</div></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-10 text-center text-sm text-[#617067]">No role-assigned staff accounts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($staff->hasPages())<div class="mt-4">{{ $staff->links() }}</div>@endif
</section>

<section class="mt-5 space-y-4">
    <div>
        <h3 class="text-lg font-bold text-[#182A22]">Role permissions</h3>
        <p class="mt-1 text-sm text-[#617067]">Access and staff-management permissions are protected and may only be granted by a super administrator.</p>
    </div>
    <div class="grid gap-4 xl:grid-cols-2">
        @foreach ($roles as $role)
            @php($protectedRole = $role->permissions->contains(fn ($permission) => in_array($permission->slug, ['admin.access', 'staff.manage'], true)))
            <div class="rounded-2xl border border-[#DCE9DF] bg-white p-5">
                <h4 class="font-bold text-[#182A22]">{{ $role->name }}</h4>
                <p class="mt-1 text-xs text-[#617067]">{{ $role->slug }}</p>
                @if ($protectedRole && !auth()->user()->is_admin)
                    <p class="mt-4 rounded-xl bg-amber-50 p-3 text-sm text-amber-900">Only a super administrator can change this role because it contains protected permissions.</p>
                    <div class="mt-3 flex flex-wrap gap-1.5">
                        @foreach ($role->permissions as $permission)
                            <span class="rounded bg-[#F0F4F1] px-2 py-1 text-xs text-[#344054]">{{ $permission->slug }}</span>
                        @endforeach
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.staff.roles.permissions', $role) }}">
                        @csrf
                        @method('PUT')
                        <div class="mt-4 grid gap-2 sm:grid-cols-2">
                            @foreach ($permissions as $permission)
                                <label class="flex items-start gap-2 rounded-lg px-2 py-2 text-sm {{ $role->permissions->contains('id', $permission->id) ? 'bg-[#F5F8F6]' : '' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->slug }}" @checked($role->permissions->contains('id', $permission->id)) class="mt-0.5 rounded border-[#B6C7BB] text-[#08713D] focus:ring-[#08713D]">
                                    <span class="leading-5 text-[#344054]">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <button class="mt-4 rounded-xl border border-[#B6D8C0] px-4 py-2.5 text-sm font-semibold text-[#04552F] hover:bg-[#E7F4EB]">Save {{ $role->name }} permissions</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</section>
@endsection
