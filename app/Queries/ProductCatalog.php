<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Product;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Collection;

final class ProductCatalog
{
    /**
     * @return Collection<int, Product>
     */
    public function publishedForLocale(string $locale): Collection
    {
        $ids = ContentCache::rememberIds(
            ContentCache::products($locale),
            fn (): array => Product::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->orderBy('sort_order')
                ->pluck('id')
                ->all(),
        );

        if ($ids === []) {
            return new Collection;
        }

        return Product::query()
            ->whereIn('id', $ids)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->orderBy('sort_order')
            ->get();
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
