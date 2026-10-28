<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Career;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Collection;

final class CareerCatalog
{
    /**
     * @return Collection<int, Career>
     */
    public function publishedForLocale(string $locale): Collection
    {
        $ids = ContentCache::rememberIds(
            ContentCache::careers($locale),
            fn (): array => Career::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->latest('published_at')
                ->pluck('id')
                ->all(),
        );

        if ($ids === []) {
            return new Collection;
        }

        return Career::query()
            ->whereIn('id', $ids)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->latest('published_at')
            ->get();
    }

    public function findBySlug(string $locale, string $slug): Career
    {
        return Career::query()
            ->publiclyVisible(now())
            ->withLocaleSlug($locale, $slug)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->firstOrFail();
    }
}
