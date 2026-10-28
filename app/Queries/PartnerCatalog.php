<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Partner;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Collection;

final class PartnerCatalog
{
    /**
     * @return Collection<int, Partner>
     */
    public function publishedForLocale(string $locale): Collection
    {
        $ids = ContentCache::rememberIds(
            ContentCache::partners($locale),
            fn (): array => Partner::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->orderBy('sort_order')
                ->pluck('id')
                ->all(),
        );

        if ($ids === []) {
            return new Collection;
        }

        return Partner::query()
            ->whereIn('id', $ids)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->orderBy('sort_order')
            ->get();
    }
}
