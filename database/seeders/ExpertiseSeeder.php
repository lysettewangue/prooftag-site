<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ExpertiseCode;
use App\Enums\PublicationStatus;
use App\Models\Expertise;
use Illuminate\Database\Seeder;

class ExpertiseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ExpertiseCode::cases() as $code) {
            $expertise = Expertise::query()->updateOrCreate(
                ['code' => $code->value],
                [
                    'sort_order' => $code->sortOrder(),
                    'publication_status' => PublicationStatus::Draft,
                ],
            );

            foreach (['fr', 'en'] as $locale) {
                $expertise->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $code->slugs()[$locale],
                        'title' => $code->titles()[$locale],
                        'summary' => null,
                        'body' => null,
                    ],
                );
            }
        }
    }
}
