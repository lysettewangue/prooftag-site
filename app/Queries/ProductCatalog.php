<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Product;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class ProductCatalog
{
    /**
     * @return Collection<int, Product>
     */
    public function publishedForLocale(string $locale): Collection
    {
        /** @var Collection<int, Product> $products */
        $products = Cache::remember(
            ContentCache::products($locale),
            (int) config('cms.cache_ttl', 3600),
            function () use ($locale): Collection {
                return Product::query()
                    ->publiclyVisible(now())
                    ->forLocale($locale)
                    ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
                    ->orderBy('sort_order')
                    ->get();
            },
        );

        return $products;
    }

    public function findBySlug(string $locale, string $slug): Product
    {
        return Product::query()
            ->publiclyVisible(now())
            ->withLocaleSlug($locale, $slug)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->firstOrFail();
    }
}
