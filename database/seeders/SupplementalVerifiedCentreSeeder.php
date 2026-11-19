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
 * Named centres corroborated by current operator pages or public reporting,
 * but absent from MINT/DTR table 73 (2022).
 */
final class SupplementalVerifiedCentreSeeder extends Seeder
{
    public function run(): void
    {
        $regions = Region::query()->get()->keyBy(fn (Region $region): string => $region->code->value);
        $verifierId = User::query()
            ->where('email', config('app.filament_admin_email'))
            ->value('id');
        $sortOrder = (int) InspectionCentre::query()->max('sort_order');

        foreach ($this->centres() as $row) {
            $region = $regions->get($row['region']->value);

            if (! $region instanceof Region) {
                continue;
            }

            $centre = InspectionCentre::query()->firstOrNew([
                'operator_name' => $row['operator'],
                'city' => $row['city'],
            ]);
            $coordinates = CameroonCityCoordinates::forCity($row['city']);

            if (! $centre->exists) {
                $sortOrder++;
            }

            $centre->forceFill([
                'region_id' => $region->id,
                'phone' => $centre->phone ?? $row['phone'],
                'email' => $centre->email ?? $row['email'],
                'latitude' => $centre->latitude ?? $coordinates['latitude'] ?? null,
                'longitude' => $centre->longitude ?? $coordinates['longitude'] ?? null,
                'verification_status' => CentreVerificationStatus::Active,
                'last_verified_at' => $centre->last_verified_at ?? now(),
                'verified_by' => $centre->verified_by ?? $verifierId,
                'sort_order' => $centre->sort_order ?? $sortOrder,
            ])->save();

            $slug = Str::slug($row['operator'].' '.$row['city']);

            $centre->translations()->updateOrCreate(
                ['locale' => 'fr'],
                [
                    'slug' => $slug,
                    'name' => $row['operator'],
                    'address_line' => $row['address'],
                    'body' => $row['source_fr'],
                ],
            );

            $centre->translations()->updateOrCreate(
                ['locale' => 'en'],
                [
                    'slug' => $slug,
                    'name' => $row['operator'],
                    'address_line' => $row['address'],
                    'body' => $row['source_en'],
                ],
            );
        }

        ContentCache::flush();
    }

    /**
     * @return list<array{
     *     operator: string,
     *     city: string,
     *     address: string,
     *     region: RegionCode,
     *     phone: string|null,
     *     email: string|null,
     *     source_fr: string,
     *     source_en: string
     * }>
     */
    private function centres(): array
    {
        return [
            [
                'operator' => 'SECOMA',
                'city' => 'Yaoundé - Nomayos',
                'address' => 'Nomayos, Yaoundé',
                'region' => RegionCode::Centre,
                'phone' => null,
                'email' => null,
                'source_fr' => 'Centre de visite technique SECOMA publiquement signalé comme opérationnel à Nomayos.',
                'source_en' => 'SECOMA technical inspection centre publicly reported as operating in Nomayos.',
            ],
            [
                'operator' => 'SAPOU TRANSPORT',
                'city' => 'Limbé - Mile 2 Bissumbu',
                'address' => 'Mile 2 Bissumbu, près de Bocom Petroleum, Limbé',
                'region' => RegionCode::SudOuest,
                'phone' => null,
                'email' => null,
                'source_fr' => 'Centre de visite technique présenté par SAPOU Transport sur sa page publique.',
                'source_en' => 'Technical inspection centre presented on SAPOU Transport’s public page.',
            ],
            [
                'operator' => 'DB CONSULTING CT',
                'city' => 'Douala - Beedi',
                'address' => 'Beedi, face Pharmacie Santa Rosa, Douala',
                'region' => RegionCode::Littoral,
                'phone' => '+237 677 169 720',
                'email' => 'contact@dbconsultingct.com',
                'source_fr' => 'Centre agréé présenté par DB Consulting CT et inauguré en septembre 2024.',
                'source_en' => 'Approved centre presented by DB Consulting CT and inaugurated in September 2024.',
            ],
        ];
    }
}
