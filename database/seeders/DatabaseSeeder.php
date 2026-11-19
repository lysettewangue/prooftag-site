<?php

declare(strict_types=1);

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
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
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
            ResourceContentMigrationSeeder::class,
            RepresentativeCentreSeeder::class,
            CentreDirectoryMigrationSeeder::class,
            Official2022CentreDirectorySeeder::class,
            SupplementalVerifiedCentreSeeder::class,
            RepresentativeEditorialSeeder::class,
            HomepageEditorialSeeder::class,
            MediaContentMigrationSeeder::class,
            LegacyRedirectSeeder::class,
        ]);
        // PreviewPresentationSeeder is local-design only:
        // php artisan db:seed --class=PreviewPresentationSeeder

    }
}
