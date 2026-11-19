<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CentreVerificationStatus;
use App\Enums\RegionCode;
use App\Models\InspectionCentre;
use App\Models\Region;
use App\Models\User;
use App\Support\CameroonCityCoordinates;
use App\Support\ContentCache;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Completes the public directory from MINT/DTR's official 2022 list.
 *
 * Source: Annuaire Statistique des Transports, edition 2023, table 73.
 * The source names 50 centres. Seventeen equivalent operator/city entries are
 * already managed by CentreDirectoryMigrationSeeder, so this seeder adds the
 * 33 missing centres without duplicating those more precisely named records.
 *
 * The 2M CAMEROUN row does not state a city in table 73; its Yaoundé location
 * is corroborated by the public business address RGVR+VC5, Yaoundé.
 */
final class Official2022CentreDirectorySeeder extends Seeder
{
    public function run(): void
    {
        $regions = Region::query()->get()->keyBy(fn (Region $region): string => $region->code->value);
        $verifierId = User::query()
            ->where('email', config('app.filament_admin_email'))
            ->value('id');
        $sortOrder = (int) InspectionCentre::query()->max('sort_order');

        foreach ($this->missingCentres() as [$operator, $city, $regionCode]) {
            $region = $regions->get($regionCode->value);

            if (! $region instanceof Region) {
                continue;
            }

            $centre = InspectionCentre::query()->firstOrNew([
                'operator_name' => $operator,
                'city' => $city,
            ]);
            $coordinates = CameroonCityCoordinates::forCity($city);

            if (! $centre->exists) {
                $sortOrder++;
            }

            $centre->forceFill([
                'region_id' => $region->id,
                'phone' => $centre->phone,
                'email' => $centre->email,
                'latitude' => $centre->latitude ?? $coordinates['latitude'] ?? null,
                'longitude' => $centre->longitude ?? $coordinates['longitude'] ?? null,
                'verification_status' => CentreVerificationStatus::Active,
                'last_verified_at' => $centre->last_verified_at ?? now(),
                'verified_by' => $centre->verified_by ?? $verifierId,
                'sort_order' => $centre->sort_order ?? $sortOrder,
            ])->save();

            $slug = Str::slug($operator.' '.$city);

            $centre->translations()->updateOrCreate(
                ['locale' => 'fr'],
                [
                    'slug' => $slug,
                    'name' => $operator,
                    'address_line' => $city,
                    'body' => 'Centre de visite technique répertorié par le Ministère des Transports (données DTR 2022).',
                ],
            );

            $centre->translations()->updateOrCreate(
                ['locale' => 'en'],
                [
                    'slug' => $slug,
                    'name' => $operator,
                    'address_line' => $city,
                    'body' => 'Technical inspection centre listed by the Ministry of Transport (DTR 2022 data).',
                ],
            );
        }

        ContentCache::flush();
    }

    /**
     * @return list<array{0: string, 1: string, 2: RegionCode}>
     */
    private function missingCentres(): array
    {
        return [
            ['2M CAMEROUN', 'Yaoundé', RegionCode::Centre],
            ['AUTOBILAN', 'Yaoundé', RegionCode::Centre],
            ['AUTO RELAX', 'Douala', RegionCode::Littoral],
            ['AUTO RELAX', 'Limbé', RegionCode::SudOuest],
            ['AUTOTECHNIQUE', 'Foumbot', RegionCode::Ouest],
            ['CADCIA SARL', 'Ngaoundéré', RegionCode::Adamaoua],
            ['CADCIA SARL', 'Yaoundé', RegionCode::Centre],
            ['CATES', 'Kribi', RegionCode::Sud],
            ['CATES', 'Yaoundé', RegionCode::Centre],
            ['CEMEX CONTROLES', 'Douala', RegionCode::Littoral],
            ['CEMEX CONTROLES', 'Ebolowa', RegionCode::Sud],
            ['CEMEX CONTROLES', 'Kribi', RegionCode::Sud],
            ['CEMEX CONTROLES', 'Yaoundé', RegionCode::Centre],
            ['COTEMA', 'Yaoundé', RegionCode::Centre],
            ['FLISES', 'Kousseri', RegionCode::ExtremeNord],
            ['FLISES', 'Maroua', RegionCode::ExtremeNord],
            ['MAGNONG', 'Mbouda', RegionCode::Ouest],
            ['MAGNONG', 'Yaoundé', RegionCode::Centre],
            ['NACHO', 'Nacho Bamenda', RegionCode::NordOuest],
            ['NACHO', 'Nkwen Bamenda', RegionCode::NordOuest],
            ['NACHO', 'Yaoundé', RegionCode::Centre],
            ['OCDM & T', 'Dschang', RegionCode::Ouest],
            ['OD AUTO TECHNICA', 'Garoua', RegionCode::Nord],
            ['OD AUTO TECHNICA', 'Guider', RegionCode::Nord],
            ['PRESTECH', 'Kumba', RegionCode::SudOuest],
            ['SECURITE AUTO SERVICE', 'Douala', RegionCode::Littoral],
            ['SILICON', 'Douala', RegionCode::Littoral],
            ['SILICON', 'Edéa', RegionCode::Littoral],
            ['SILICON', 'Nkongsamba', RegionCode::Littoral],
            ['SILICON', 'Yaoundé', RegionCode::Centre],
            ['SMT', 'Kribi', RegionCode::Sud],
            ['VISITECH', 'Douala', RegionCode::Littoral],
            ['VITECH', 'Yaoundé', RegionCode::Centre],
        ];
    }
}
