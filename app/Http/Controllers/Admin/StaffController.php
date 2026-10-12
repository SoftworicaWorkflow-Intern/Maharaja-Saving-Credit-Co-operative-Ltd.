<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRoleRequest;
use App\Http\Requests\UpdateRolePermissionsRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staff = User::query()
            ->with('roles.permissions')
            ->where(fn ($query) => $query->where('is_admin', true)->orWhereHas('roles'))
            ->orderBy('name')
            ->paginate(25);

        $roles = Role::query()
            ->with('permissions')
            ->orderBy('name')
            ->get();

        $permissions = Permission::query()
            ->orderBy('slug')
            ->get();

        return view('admin.staff.index', compact('staff', 'roles', 'permissions'));
    }

    public function assignRole(StoreStaffRoleRequest $request): RedirectResponse
    {
        $user = User::query()->where('email', $request->validated('email'))->firstOrFail();
        $role = Role::query()->with('permissions')->where('slug', $request->validated('role'))->firstOrFail();

        if (! $request->user()->is_admin
            && $role->permissions->contains(fn (Permission $permission): bool => in_array(
                $permission->slug,
                ['admin.access', 'staff.manage'],
                true,
            ))) {
            throw ValidationException::withMessages([
                'role' => 'Only a super administrator can assign this privileged role.',
            ]);
        }

        $user->roles()->syncWithoutDetaching([$role->id]);

        return redirect()->route('admin.staff.index')->with('status', 'Role assigned to the staff account.');
    }

    public function updatePermissions(
        UpdateRolePermissionsRequest $request,
        Role $role,
    ): RedirectResponse {
        if (! $request->user()->is_admin
            && $role->permissions()->whereIn('slug', ['admin.access', 'staff.manage'])->exists()) {
            abort(403);
        }

        $permissionIds = Permission::query()
            ->whereIn('slug', $request->validated('permissions', []))
            ->pluck('id');

        DB::transaction(function () use ($role, $permissionIds, $request): void {
            $lockedRole = Role::query()->lockForUpdate()->findOrFail($role->id);
            $lockedRole->permissions()->sync($permissionIds);

        });

        return redirect()->route('admin.staff.index')->with('status', 'Role permissions updated.');
    }
}
