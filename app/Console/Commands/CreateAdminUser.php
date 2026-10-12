<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('admin:create')]
#[Description('Create an administrator account using secure interactive prompts')]
class CreateAdminUser extends Command
{
    public function handle(): int
    {
        if (! $this->input->isInteractive()) {
            $this->error('This command must be run interactively so the password can be entered securely.');

            return self::FAILURE;
        }

        $name = trim((string) $this->ask('Administrator full name'));
        $email = strtolower(trim((string) $this->ask('Administrator email address')));

        if ($name === '' || mb_strlen($name) > 255) {
            $this->error('Enter a name between 1 and 255 characters.');

            return self::FAILURE;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Enter a valid email address.');

            return self::FAILURE;
        }

        if (User::query()->where('email', $email)->exists()) {
            $this->error('An account already uses that email address. No account was changed.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('Administrator password (at least 12 characters)');
        $confirmation = (string) $this->secret('Confirm administrator password');

        if (mb_strlen($password) < 12 || $password !== $confirmation) {
            $this->error('Passwords must match and be at least 12 characters long.');

            return self::FAILURE;
        }

        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->forceFill(['is_admin' => true]);
        $user->save();

        $this->components->info("Administrator account created for {$email}.");

        return self::SUCCESS;
    }
}
