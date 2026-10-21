<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ControlledStatusSet;
use App\Models\ControlledStatus;
use Illuminate\Database\Seeder;

class ControlledStatusSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ControlledStatusSet::cases() as $set) {
            foreach ($set->codes() as $index => $code) {
                ControlledStatus::query()->updateOrCreate(
                    [
                        'set' => $set->value,
                        'code' => $code,
                    ],
                    [
                        'sort_order' => $index + 1,
                    ],
                );
            }
        }
    }
}
