<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('admin:password-reset')]
#[Description('Reset an administrator password using secure interactive prompts')]
class ResetAdminPassword extends Command
{
    public function handle(): int
    {
        if (! $this->input->isInteractive()) {
            $this->error('This command must be run interactively so the password can be entered securely.');

            return self::FAILURE;
        }

        $email = strtolower(trim((string) $this->ask('Administrator email address')));
        $admin = User::query()
            ->where('email', $email)
            ->where('is_admin', true)
            ->first();

        if ($admin === null) {
            $this->error('No administrator account was found for that email address.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('New administrator password (at least 12 characters)');
        $confirmation = (string) $this->secret('Confirm new administrator password');

        if (mb_strlen($password) < 12 || $password !== $confirmation) {
            $this->error('Passwords must match and be at least 12 characters long. No password was changed.');

            return self::FAILURE;
        }

        $admin->password = $password;
        $admin->save();

        $this->components->info("Administrator password updated for {$email}.");

        return self::SUCCESS;
    }
}
