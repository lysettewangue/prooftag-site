<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StaffRole;
use App\Models\User;
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
        $this->call([
            RoleSeeder::class,
            RegionSeeder::class,
            ControlledStatusSeeder::class,
            SiteSettingSeeder::class,
            PageSeeder::class,
            ProductSeeder::class,
            ExpertiseSeeder::class,
            SecurityPointSeeder::class,
            EquipmentSeeder::class,
            LibraryDocumentSeeder::class,
        ]);

        $email = config('app.filament_admin_email');

        if (! is_string($email) || $email === '') {
            return;
        }

        User::query()
            ->where('email', $email)
            ->first()
            ?->assignStaffRole(StaffRole::SuperAdmin);
    }
}
