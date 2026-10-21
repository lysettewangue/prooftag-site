<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ProductCode::cases() as $code) {
            $product = Product::query()->updateOrCreate(
                ['code' => $code->value],
                [
                    'public_name' => $code->publicName(),
                    'sort_order' => $code->sortOrder(),
                    'slug_fragment' => $code->slugFragment(),
                    'external_url' => null,
                    'publication_status' => PublicationStatus::Draft,
                ],
            );

            foreach (['fr', 'en'] as $locale) {
                $product->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $code->slugFragment(),
                        'summary' => null,
                        'body' => null,
                    ],
                );
            }
        }
    }
}
