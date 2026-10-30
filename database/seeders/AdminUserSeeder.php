<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use InvalidArgumentException;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = strtolower(trim((string) config('services.admin.email')));
        $password = (string) config('services.admin.password');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Set a valid ADMIN_EMAIL before seeding the administrator.');
        }

        if (mb_strlen($password) < 12) {
            throw new InvalidArgumentException('Set ADMIN_PASSWORD to at least 12 characters before seeding the administrator.');
        }

        $existingUser = User::query()->where('email', $email)->first();

        if ($existingUser !== null) {
            if (! $existingUser->is_admin) {
                throw new RuntimeException('The configured ADMIN_EMAIL belongs to a non-administrator account. No account was changed.');
            }

            $this->command?->info("Administrator account {$email} already exists; its password was not changed.");

            return;
        }

        $admin = new User;
        $admin->name = 'Maharaja Administrator';
        $admin->email = $email;
        $admin->password = $password;
        $admin->forceFill(['is_admin' => true]);
        $admin->save();

        $this->command?->info("Administrator account created for {$email}.");
    }
}
