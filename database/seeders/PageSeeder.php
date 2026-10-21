<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PageCode;
use App\Enums\PublicationStatus;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PageCode::cases() as $code) {
            $page = Page::query()->updateOrCreate(
                ['code' => $code->value],
                ['publication_status' => PublicationStatus::Draft],
            );

            foreach (['fr', 'en'] as $locale) {
                $page->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $code->slugs()[$locale],
                        'title' => $code->titles()[$locale],
                        'blocks' => [],
                    ],
                );
            }
        }
    }
}
