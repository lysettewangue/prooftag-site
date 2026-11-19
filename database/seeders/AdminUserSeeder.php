<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StaffRole;
use App\Models\User;
use Illuminate\Database\Seeder;

final class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('app.filament_admin_email');
        $password = config('app.filament_admin_password');

        if (! is_string($email) || $email === '' || ! is_string($password) || $password === '') {
            return;
        }

        $user = User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Administrateur PROOFTAG-CATIS',
                'password' => $password,
                'email_verified_at' => now(),
            ],
        );

        $user->assignStaffRole(StaffRole::SuperAdmin);
    }
}
