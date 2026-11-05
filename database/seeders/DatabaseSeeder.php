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
            CorporateContentMigrationSeeder::class,
            ProductSeeder::class,
            ProductContentMigrationSeeder::class,
            ExpertiseSeeder::class,
            ExpertiseContentMigrationSeeder::class,
            SecurityPointSeeder::class,
            TechnicalInspectionContentMigrationSeeder::class,
            EquipmentSeeder::class,
            EquipmentContentMigrationSeeder::class,
            LibraryDocumentSeeder::class,
            ResourceContentMigrationSeeder::class,
            RepresentativeCentreSeeder::class,
            CentreDirectoryMigrationSeeder::class,
            RepresentativeEditorialSeeder::class,
            EditorialContentMigrationSeeder::class,
            MediaContentMigrationSeeder::class,
            LegacyRedirectSeeder::class,
        ]);
        // PreviewPresentationSeeder is local-design only:
        // php artisan db:seed --class=PreviewPresentationSeeder

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
