<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\InspectionCentre;
use App\Support\ContentCache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class CentreDirectory
{
    /**
     * @return Collection<int, InspectionCentre>
     */
    public function listedForLocale(string $locale): Collection
    {
        $ids = ContentCache::rememberIds(
            ContentCache::centres($locale),
            fn (): array => InspectionCentre::query()
                ->publiclyListed()
                ->forLocale($locale)
                ->orderBy('sort_order')
                ->orderBy('city')
                ->pluck('id')
                ->all(),
        );

        if ($ids === []) {
            return new Collection;
        }

        return InspectionCentre::query()
            ->whereIn('id', $ids)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale), 'region'])
            ->orderBy('sort_order')
            ->orderBy('city')
            ->get();
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
