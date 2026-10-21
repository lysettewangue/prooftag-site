<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\InspectionCentre;
use App\Support\ContentCache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class CentreDirectory
{
    /**
     * @return Collection<int, InspectionCentre>
     */
    public function listedForLocale(string $locale): Collection
    {
        /** @var Collection<int, InspectionCentre> $centres */
        $centres = Cache::remember(
            ContentCache::centres($locale),
            (int) config('cms.cache_ttl', 3600),
            function () use ($locale): Collection {
                return InspectionCentre::query()
                    ->publiclyListed()
                    ->forLocale($locale)
                    ->with(['translations' => fn ($query) => $query->where('locale', $locale), 'region'])
                    ->orderBy('sort_order')
                    ->orderBy('city')
                    ->get();
            },
        );

        return $centres;
    }

    /**
     * @return LengthAwarePaginator<int, InspectionCentre>
     */
    public function paginatePublic(string $locale, ?int $regionId = null, ?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = InspectionCentre::query()
            ->publiclyListed()
            ->forLocale($locale)
            ->with(['translations' => fn ($q) => $q->where('locale', $locale), 'region']);

        if ($regionId !== null) {
            $query->where('region_id', $regionId);
        }

        if (is_string($search) && $search !== '') {
            $like = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function ($inner) use ($locale, $like): void {
                $inner
                    ->where('operator_name', 'like', $like)
                    ->orWhere('city', 'like', $like)
                    ->orWhereHas('translations', function ($translations) use ($locale, $like): void {
                        $translations->where('locale', $locale)->where('name', 'like', $like);
                    });
            });
        }

        return $query->orderBy('sort_order')->orderBy('city')->paginate($perPage);
    }

    public function findBySlug(string $locale, string $slug): InspectionCentre
    {
        return InspectionCentre::query()
            ->publiclyListed()
            ->withLocaleSlug($locale, $slug)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale), 'region'])
            ->firstOrFail();
    }
}
