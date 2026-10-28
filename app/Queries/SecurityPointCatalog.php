<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\SecurityPoint;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Collection;

final class SecurityPointCatalog
{
    /**
     * @return Collection<int, SecurityPoint>
     */
    public function publishedForLocale(string $locale): Collection
    {
        $ids = ContentCache::rememberIds(
            ContentCache::securityPoints($locale),
            fn (): array => SecurityPoint::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->orderBy('sort_order')
                ->pluck('id')
                ->all(),
        );

        if ($ids === []) {
            return new Collection;
        }

        return SecurityPoint::query()
            ->whereIn('id', $ids)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->orderBy('sort_order')
            ->get();
    }
}
