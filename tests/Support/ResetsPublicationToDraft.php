<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Enums\PublicationStatus;
use App\Models\Equipment;
use App\Models\Expertise;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Resource;
use App\Models\SecurityPoint;
use App\Support\ContentCache;

trait ResetsPublicationToDraft
{
    protected function resetPublicationToDraft(): void
    {
        foreach ([
            Product::class,
            Page::class,
            Expertise::class,
            SecurityPoint::class,
            Equipment::class,
            Post::class,
            Resource::class,
        ] as $model) {
            $model::query()->update([
                'publication_status' => PublicationStatus::Draft->value,
                'published_at' => null,
                'scheduled_for' => null,
            ]);
        }

        ContentCache::flush();
    }
}
