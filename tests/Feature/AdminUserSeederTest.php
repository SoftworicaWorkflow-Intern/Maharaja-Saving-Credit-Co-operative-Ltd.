<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use RuntimeException;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeding_creates_the_configured_admin_account(): void
    {
        config([
            'services.admin.email' => 'admin@example.com',
            'services.admin.password' => 'secure admin password',
        ]);

        $this->seed();

        $this->assertDatabaseHas('users', [
            'name' => 'Maharaja Administrator',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('secure admin password', $admin->password));
    }

    public function test_reseeding_does_not_replace_an_existing_admin_password(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $admin->forceFill(['is_admin' => true])->save();
        $originalPassword = $admin->password;
        config([
            'services.admin.email' => 'admin@example.com',
            'services.admin.password' => 'another secure password',
        ]);

        $this->seed(AdminUserSeeder::class);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'password' => $originalPassword,
            'is_admin' => true,
        ]);
    }

    public function test_seeder_does_not_promote_a_non_admin_account(): void
    {
        $user = User::factory()->create(['email' => 'admin@example.com']);
        config([
            'services.admin.email' => 'admin@example.com',
            'services.admin.password' => 'secure admin password',
        ]);

        try {
            $this->seed(AdminUserSeeder::class);
            $this->fail('Seeding must not promote a non-administrator account.');
        } catch (RuntimeException $exception) {
            $this->assertSame(
                'The configured ADMIN_EMAIL belongs to a non-administrator account. No account was changed.',
                $exception->getMessage(),
            );
        }

        $this->assertFalse($user->refresh()->is_admin);
    }

    public function test_seeder_requires_a_valid_email_and_password(): void
    {
        config([
            'services.admin.email' => 'not-an-email',
            'services.admin.password' => 'short',
        ]);
        $this->expectException(InvalidArgumentException::class);

        $this->seed(AdminUserSeeder::class);
    }
}
