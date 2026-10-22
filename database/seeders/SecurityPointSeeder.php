<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Enums\SecurityPoint as SecurityPointSlot;
use App\Models\SecurityPoint;
use Illuminate\Database\Seeder;

class SecurityPointSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SecurityPointSlot::cases() as $point) {
            $record = SecurityPoint::query()->updateOrCreate(
                ['sort_order' => $point->value],
                ['publication_status' => PublicationStatus::Draft],
            );

            foreach (['fr', 'en'] as $locale) {
                $record->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'title' => $point->titles()[$locale],
                        'body' => $locale === 'fr'
                            ? 'Composant du workflow CT (AUD-006). Réécriture requise (AUD-001). Ne pas publier comme description 2020 à jour.'
                            : 'CT workflow component (AUD-006). Rewrite required (AUD-001). Do not publish as current 2020 copy.',
                    ],
                );
            }
        }
    }
}
