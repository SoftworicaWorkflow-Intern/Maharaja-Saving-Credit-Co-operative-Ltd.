<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);
        $this->call(NoticeSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(FaqSeeder::class);

        if (blank(config('services.admin.password'))) {
            $this->command?->warn('ADMIN_PASSWORD is not configured; administrator account creation was skipped.');

            return;
        }

        $this->call(AdminUserSeeder::class);
    }
}
