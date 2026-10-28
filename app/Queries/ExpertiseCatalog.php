<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Expertise;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Collection;

final class ExpertiseCatalog
{
    /**
     * @return Collection<int, Expertise>
     */
    public function publishedForLocale(string $locale): Collection
    {
        $ids = ContentCache::rememberIds(
            ContentCache::expertises($locale),
            fn (): array => Expertise::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->orderBy('sort_order')
                ->pluck('id')
                ->all(),
        );

        if ($ids === []) {
            return new Collection;
        }

        return Expertise::query()
            ->whereIn('id', $ids)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->orderBy('sort_order')
            ->get();
    }

    public function findBySlug(string $locale, string $slug): Expertise
    {
        return Expertise::query()
            ->publiclyVisible(now())
            ->withLocaleSlug($locale, $slug)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->firstOrFail();
    }
}