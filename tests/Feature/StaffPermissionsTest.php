<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_administrator_can_manage_staff_roles_and_permissions(): void
    {
        $this->seed();
        $admin = $this->administrator();
        $applicant = User::factory()->create(['email' => 'staff@example.com']);

        $this->actingAs($admin)
            ->post(route('admin.staff.roles.assign'), [
                'email' => $applicant->email,
                'role' => 'member_service_officer',
            ])
            ->assertRedirect(route('admin.staff.index'));

        $role = Role::query()->where('slug', 'member_service_officer')->firstOrFail();
        $role->permissions()->attach(Permission::query()->where('slug', 'members.view')->firstOrFail());

        $this->actingAs($admin)
            ->get(route('admin.staff.index'))
            ->assertOk()
            ->assertSee('staff@example.com')
            ->assertSee('View member records');
    }

    public function test_staff_manager_cannot_grant_privileged_permissions_or_roles(): void
    {
        $this->seed();
        $manager = User::factory()->create();
        $managerRole = Role::query()->where('slug', 'member_service_officer')->firstOrFail();
        $managerRole->permissions()->attach([
            Permission::query()->where('slug', 'admin.access')->firstOrFail()->id,
            Permission::query()->where('slug', 'staff.manage')->firstOrFail()->id,
        ]);
        $manager->roles()->attach($managerRole);

        $target = User::factory()->create(['email' => 'target@example.com']);
        $privilegedRole = Role::query()->where('slug', 'manager')->firstOrFail();
        $privilegedRole->permissions()->attach(
            Permission::query()->where('slug', 'admin.access')->firstOrFail(),
        );

        $this->actingAs($manager)
            ->post(route('admin.staff.roles.assign'), [
                'email' => $target->email,
                'role' => $privilegedRole->slug,
            ])
            ->assertSessionHasErrors('role');

        $this->actingAs($manager)
            ->put(route('admin.staff.roles.permissions', $managerRole), [
                'permissions' => ['admin.access', 'members.view'],
            ])
            ->assertSessionHasErrors('permissions.0');

        $this->assertDatabaseMissing('role_user', [
            'user_id' => $target->id,
            'role_id' => $privilegedRole->id,
        ]);
        $this->assertTrue($manager->fresh()->hasPermissionTo('staff.manage'));
    }

    public function test_member_without_staff_permissions_cannot_open_staff_management(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.staff.index'))
            ->assertForbidden();
    }

    private function administrator(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        return $admin;
    }
}
