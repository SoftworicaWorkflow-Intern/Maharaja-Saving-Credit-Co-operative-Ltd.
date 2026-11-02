<?php

namespace Database\Seeders;

use App\Models\MembershipApplication;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoMemberSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            $this->command?->warn('Demo members can only be seeded in local or testing environments.');

            return;
        }

        $memberNames = [
            2 => 'Suman Adhikari',
            3 => 'Binita Gurung',
            4 => 'Prakash Thapa',
            5 => 'Anisha Karki',
            6 => 'Deepak Bhandari',
            7 => 'Sabina Tamang',
            8 => 'Rajesh Shrestha',
            9 => 'Mina Poudel',
            10 => 'Nabin Khadka',
        ];

        foreach ($memberNames as $number => $name) {
            $email = sprintf('demo-member-%02d@demo.invalid', $number);
            $user = User::query()->firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => Str::random(64)],
            );

            if ($user->name !== $name) {
                $user->forceFill(['name' => $name])->save();
            }

            $application = $user->membershipApplications()->first();

            if ($application === null) {
                $application = $user->membershipApplications()->create([
                    'phone' => '0000000000',
                    'address' => 'Sample data only; not a real member.',
                    'status' => MembershipApplication::APPROVED,
                    'review_reason' => 'Sample/demo record; not a real member.',
                    'submitted_at' => now()->subDays($number),
                ]);
            }

            if (! $user->member()->exists()) {
                $user->member()->create([
                    'membership_application_id' => $application->id,
                    'member_number' => sprintf('SAMPLE-MBR-%03d', $number),
                    'phone' => '0000000000',
                    'address' => 'Sample data only; not a real member.',
                    'status' => 'active',
                    'joined_at' => now()->subDays($number),
                ]);
            }
        }

        $this->command?->info('Seeded nine clearly labeled demo members for local display.');
    }
}
