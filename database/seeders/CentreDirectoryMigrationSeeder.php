<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CentreVerificationStatus;
use App\Enums\RegionCode;
use App\Models\InspectionCentre;
use App\Models\Region;
use App\Support\ContentCache;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Step 156 — migrate the www centre capture (AUD-007).
 *
 * Imports every operator×city pair from the 2026-10-20 extract as Unverified by default.
 * Preserves verification_status (and last_verified_at) for rows already Active from step 109.
 * Does not invent phone, email, or coordinates. VER-03 stays Open (~35 claim incomplete).
 */
class CentreDirectoryMigrationSeeder extends Seeder
{
    public function run(): void
    {
        $sort = 0;

        foreach ($this->centres() as $row) {
            $sort++;
            $region = Region::query()->where('code', $row['region']->value)->firstOrFail();

            $centre = InspectionCentre::query()->firstOrNew([
                'operator_name' => $row['operator_name'],
                'city' => $row['city'],
            ]);

            $preservedStatus = $centre->exists
                ? $centre->verification_status
                : CentreVerificationStatus::Unverified;
            $preservedVerifiedAt = $centre->exists ? $centre->last_verified_at : null;
            $preservedVerifiedBy = $centre->exists ? $centre->verified_by : null;

            // Never invent contact or geo on migration. Keep any values already set in admin.
            $centre->forceFill([
                'region_id' => $region->id,
                'phone' => $centre->phone,
                'email' => $centre->email,
                'latitude' => $centre->latitude,
                'longitude' => $centre->longitude,
                'verification_status' => $preservedStatus,
                'last_verified_at' => $preservedStatus === CentreVerificationStatus::Active
                    ? ($preservedVerifiedAt ?? now())
                    : $preservedVerifiedAt,
                'verified_by' => $preservedVerifiedBy,
                'sort_order' => $centre->sort_order ?? $sort,
            ])->save();

            $slug = $row['slug'];

            foreach (['fr', 'en'] as $locale) {
                $centre->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $slug,
                        'name' => $row['operator_name'],
                        'address_line' => $row['city'],
                        'body' => $preservedStatus === CentreVerificationStatus::Unverified
                            ? ($locale === 'fr'
                                ? 'Import www 2026-10-20. Statut non vérifié (VER-03). Téléphone, e-mail et coordonnées non inventés.'
                                : 'Imported from www 2026-10-20. Unverified (VER-03). Phone, email, and coordinates not invented.')
                            : ($centre->translationFor($locale)?->body),
                    ],
                );
            }
        }

        $this->demoteOrphanActiveCentres();
        $this->ensurePublicCentresHaveEnglish();

        ContentCache::flush();
    }

    /**
     * Local leftover Active rows not in the www capture must not stay public FR-only.
     */
    private function demoteOrphanActiveCentres(): void
    {
        $known = collect($this->centres())
            ->map(fn (array $row): string => $row['operator_name'].'|'.$row['city'])
            ->all();

        InspectionCentre::query()
            ->where('verification_status', CentreVerificationStatus::Active)
            ->get()
            ->each(function (InspectionCentre $centre) use ($known): void {
                $key = $centre->operator_name.'|'.$centre->city;
                if (in_array($key, $known, true)) {
                    return;
                }

                $centre->forceFill([
                    'verification_status' => CentreVerificationStatus::Unverified,
                    'last_verified_at' => null,
                    'verified_by' => null,
                ])->save();
            });
    }

    /**
     * Active centres need FR+EN slugs so locale switch and hreflang stay paired.
     */
    private function ensurePublicCentresHaveEnglish(): void
    {
        foreach (InspectionCentre::query()->publiclyListed()->with('translations')->get() as $centre) {
            $fr = $centre->translationFor('fr');
            if ($fr === null || $fr->slug === '') {
                continue;
            }

            $centre->translations()->updateOrCreate(
                ['locale' => 'en'],
                [
                    'slug' => $fr->slug,
                    'name' => $fr->name,
                    'address_line' => $fr->address_line,
                    'body' => $centre->translationFor('en')?->body ?: $fr->body,
                ],
            );
        }
    }

    /**
     * Captured operator×city pairs (incomplete vs ~35 claim).
     *
     * @return list<array{operator_name: string, city: string, region: RegionCode, slug: string}>
     */
    private function centres(): array
    {
        $rows = [
            // Preserved Active subset (109) appears first; status is preserved on upsert.
            ['CADCIA SARL', 'Douala', RegionCode::Littoral],
            ['HYDRAC', 'Douala Bassa', RegionCode::Littoral],
            ['AUTO VISION', 'Yaoundé', RegionCode::Centre],
            ['SATELLITE NGONO', 'Garoua', RegionCode::Nord],

            // Additional capture rows — import Unverified.
            ['AUTO VISION', 'Douala', RegionCode::Littoral],
            ['AUTO VISION', 'Yassa', RegionCode::Littoral],
            ['SATELLITE NGONO', 'Yaoundé', RegionCode::Centre],
            ['SATELLITE NGONO', 'Douala Bonaberi', RegionCode::Littoral],
            ['WORLD CAR INDUSTRY', 'Yaoundé', RegionCode::Centre],
            ['WORLD CAR INDUSTRY', 'Douala Bassa', RegionCode::Littoral],
            ['WORLD CAR INDUSTRY', 'Bamenda', RegionCode::NordOuest],
            ['WORLD CAR INDUSTRY', 'Ombe', RegionCode::SudOuest],
            ['WORLD CAR INDUSTRY', 'Bafoussam', RegionCode::Ouest],
            ['GILBERT-JOSEPHINE', 'Bertoua', RegionCode::Est],
            ['GILBERT-JOSEPHINE', 'Douala Bonaberi', RegionCode::Littoral],
            ['DIAGNOSTIC AUTOMOBILE', 'Douala Yassa', RegionCode::Littoral],
            ['DIAGNOSTIC AUTOMOBILE', 'Bandjoun', RegionCode::Ouest],
        ];

        $out = [];

        foreach ($rows as [$operator, $city, $region]) {
            $out[] = [
                'operator_name' => $operator,
                'city' => $city,
                'region' => $region,
                'slug' => Str::slug($operator.' '.$city),
            ];
        }

        return $out;
    }
}
