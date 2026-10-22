<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CentreVerificationStatus;
use App\Enums\RegionCode;
use App\Models\InspectionCentre;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RepresentativeCentreSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->centres() as $row) {
            $region = Region::query()->where('code', $row['region'])->firstOrFail();

            $centre = InspectionCentre::query()->updateOrCreate(
                [
                    'operator_name' => $row['operator_name'],
                    'city' => $row['city'],
                ],
                [
                    'region_id' => $region->id,
                    'phone' => null,
                    'email' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'verification_status' => $row['status'],
                    'last_verified_at' => $row['status'] === CentreVerificationStatus::Active ? now() : null,
                    'verified_by' => null,
                    'sort_order' => $row['sort_order'],
                ],
            );

            foreach (['fr', 'en'] as $locale) {
                $centre->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $row['slug'],
                        'name' => $row['operator_name'],
                        'address_line' => $row['city'],
                        'body' => $row['status'] === CentreVerificationStatus::Unverified
                            ? ($locale === 'fr'
                                ? 'Sous-ensemble local. VER-03 reste ouvert (~35, doublons). Ne pas inventer téléphone, e-mail ou coordonnées.'
                                : 'Local subset. VER-03 stays open (~35, duplicates). Do not invent phone, email, or coordinates.')
                            : null,
                    ],
                );
            }
        }
    }

    /**
     * @return list<array{
     *     operator_name: string,
     *     city: string,
     *     region: RegionCode,
     *     status: CentreVerificationStatus,
     *     slug: string,
     *     sort_order: int
     * }>
     */
    private function centres(): array
    {
        return [
            [
                'operator_name' => 'CADCIA SARL',
                'city' => 'Douala',
                'region' => RegionCode::Littoral,
                'status' => CentreVerificationStatus::Active,
                'slug' => 'cadcia-sarl-douala',
                'sort_order' => 1,
            ],
            [
                'operator_name' => 'HYDRAC',
                'city' => 'Douala Bassa',
                'region' => RegionCode::Littoral,
                'status' => CentreVerificationStatus::Active,
                'slug' => 'hydrac-douala-bassa',
                'sort_order' => 2,
            ],
            [
                'operator_name' => 'AUTO VISION',
                'city' => 'Yaoundé',
                'region' => RegionCode::Centre,
                'status' => CentreVerificationStatus::Active,
                'slug' => 'auto-vision-yaounde',
                'sort_order' => 3,
            ],
            [
                'operator_name' => 'SATELLITE NGONO',
                'city' => 'Garoua',
                'region' => RegionCode::Nord,
                'status' => CentreVerificationStatus::Unverified,
                'slug' => 'satellite-ngono-garoua',
                'sort_order' => 4,
            ],
        ];
    }
}
