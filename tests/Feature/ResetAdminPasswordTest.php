<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetAdminPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_interactive_command_changes_the_password_for_an_administrator(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $admin->forceFill(['is_admin' => true])->save();
        $newPassword = 'a sufficiently long password';

        $this->artisan('admin:password-reset')
            ->expectsQuestion('Administrator email address', 'admin@example.com')
            ->expectsQuestion('New administrator password (at least 12 characters)', $newPassword)
            ->expectsQuestion('Confirm new administrator password', $newPassword)
            ->doesntExpectOutputToContain($newPassword)
            ->expectsOutputToContain('Administrator password updated for admin@example.com.')
            ->assertExitCode(0);

        $admin->refresh();
        $this->assertTrue(Hash::check($newPassword, $admin->password));
    }

    public function test_command_does_not_change_password_for_a_non_admin_account(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $originalPassword = $user->password;

        $this->artisan('admin:password-reset')
            ->expectsQuestion('Administrator email address', 'user@example.com')
            ->expectsOutputToContain('No administrator account was found for that email address.')
            ->assertExitCode(1);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'password' => $originalPassword,
        ]);
    }

    public function test_command_does_not_change_password_when_confirmation_fails(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $admin->forceFill(['is_admin' => true])->save();
        $originalPassword = $admin->password;

        $this->artisan('admin:password-reset')
            ->expectsQuestion('Administrator email address', 'admin@example.com')
            ->expectsQuestion('New administrator password (at least 12 characters)', 'a sufficiently long password')
            ->expectsQuestion('Confirm new administrator password', 'a different password value')
            ->expectsOutputToContain('Passwords must match and be at least 12 characters long. No password was changed.')
            ->assertExitCode(1);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'password' => $originalPassword,
        ]);
    }

    public function test_command_rejects_non_interactive_execution(): void
    {
        $this->artisan('admin:password-reset', ['--no-interaction' => true])
            ->expectsOutputToContain('This command must be run interactively so the password can be entered securely.')
            ->assertExitCode(1);
    }
}
