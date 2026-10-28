<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\EquipmentLifecycle;
use App\Models\Equipment;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Collection;

final class EquipmentCatalog
{
    /**
     * @return Collection<int, Equipment>
     */
    public function publishedForLocale(string $locale): Collection
    {
        $ids = ContentCache::rememberIds(
            ContentCache::equipment($locale),
            fn (): array => Equipment::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->orderByRaw("CASE WHEN lifecycle = ? THEN 0 ELSE 1 END", [EquipmentLifecycle::Current->value])
                ->orderBy('sort_order')
                ->pluck('id')
                ->all(),
        );

        if ($ids === []) {
            return new Collection;
        }

        return Equipment::query()
            ->whereIn('id', $ids)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->orderByRaw("CASE WHEN lifecycle = ? THEN 0 ELSE 1 END", [EquipmentLifecycle::Current->value])
            ->orderBy('sort_order')
            ->get();
    }

    public function findBySlug(string $locale, string $slug): Equipment
    {
        return Equipment::query()
            ->publiclyVisible(now())
            ->withLocaleSlug($locale, $slug)
            ->with([
                'translations' => fn ($query) => $query->where('locale', $locale),
                'media',
            ])
            ->firstOrFail();
    }
}
