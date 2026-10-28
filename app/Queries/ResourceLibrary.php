<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\ResourceRegulatoryStatus;
use App\Models\Resource;
use App\Support\ContentCache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class ResourceLibrary
{
    /**
     * @return Collection<int, Resource>
     */
    public function publishedForLocale(string $locale): Collection
    {
        $ids = ContentCache::rememberIds(
            ContentCache::resources($locale),
            fn (): array => Resource::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->latest('published_at')
                ->pluck('id')
                ->all(),
        );

        if ($ids === []) {
            return new Collection;
        }

        return Resource::query()
            ->whereIn('id', $ids)
            ->with([
                'translations' => fn ($query) => $query->where('locale', $locale),
                'media',
            ])
            ->latest('published_at')
            ->get();
    }

    /**
     * @return LengthAwarePaginator<int, Resource>
     */
    public function paginatePublic(string $locale, ?ResourceRegulatoryStatus $status = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Resource::query()
            ->publiclyVisible(now())
            ->forLocale($locale)
            ->with([
                'translations' => fn ($q) => $q->where('locale', $locale),
                'media',
            ]);

        if ($status !== null) {
            $query->where('regulatory_status', $status);
        }

        return $query->latest('published_at')->paginate($perPage);
    }

    public function findBySlug(string $locale, string $slug): Resource
    {
        return Resource::query()
            ->publiclyVisible(now())
            ->withLocaleSlug($locale, $slug)
            ->with([
                'translations' => fn ($query) => $query->where('locale', $locale),
                'media',
            ])
            ->firstOrFail();
    }
}
