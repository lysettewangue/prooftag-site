<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\ResourceRegulatoryStatus;
use App\Models\Resource;
use App\Support\ContentCache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class ResourceLibrary
{
    /**
     * @return Collection<int, CmsResourceModel>
     */
    public function publishedForLocale(string $locale): Collection
    {
        /** @var Collection<int, CmsResourceModel> $resources */
        $resources = Cache::remember(
            ContentCache::resources($locale),
            (int) config('cms.cache_ttl', 3600),
            function () use ($locale): Collection {
                return Resource::query()
                    ->publiclyVisible(now())
                    ->forLocale($locale)
                    ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
                    ->latest('published_at')
                    ->get();
            },
        );

        return $resources;
    }

    /**
     * @return LengthAwarePaginator<int, CmsResourceModel>
     */
    public function paginatePublic(string $locale, ?ResourceRegulatoryStatus $status = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Resource::query()
            ->publiclyVisible(now())
            ->forLocale($locale)
            ->with(['translations' => fn ($q) => $q->where('locale', $locale)]);

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
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->firstOrFail();
    }
}
