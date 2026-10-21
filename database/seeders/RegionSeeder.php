<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RegionCode;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RegionCode::cases() as $code) {
            $region = Region::query()->updateOrCreate(
                ['code' => $code->value],
                ['code' => $code->value],
            );

            foreach (['fr', 'en'] as $locale) {
                $region->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'name' => $code->names()[$locale],
                        'slug' => $code->slugs()[$locale],
                    ],
                );
            }
        }
    }
}
