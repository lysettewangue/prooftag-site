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
                        'body' => '',
                    ],
                );
            }
        }
    }
}
