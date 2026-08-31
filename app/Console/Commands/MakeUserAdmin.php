<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeUserAdmin extends Command
{
    protected $signature = 'user:make-admin
                            {email : E-Mail-Adresse des Benutzerkontos}
                            {--force : Beförderung ohne Rückfrage bestätigen}';

    protected $description = 'Gibt einem bestehenden Benutzerkonto die Admin-Rolle';

    public function handle(): int
    {
        $email = Str::lower(trim((string) $this->argument('email')));
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            $this->error("Kein Benutzerkonto mit der E-Mail-Adresse {$email} gefunden.");

            return self::FAILURE;
        }

        if ($user->isAdmin()) {
            $this->info("{$user->name} ist bereits Admin.");

            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm("Soll {$user->name} ({$user->email}) wirklich Admin werden?")) {
            $this->warn('Die Admin-Rolle wurde nicht geändert.');

            return self::FAILURE;
        }

        $user->forceFill(['role' => UserRole::Admin])->save();
        $this->info("{$user->name} kann sich jetzt als Admin anmelden.");

        return self::SUCCESS;
    }
}
