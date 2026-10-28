<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\TeamMember;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Collection;

final class TeamCatalog
{
    /**
     * @return Collection<int, TeamMember>
     */
    public function publishedForLocale(string $locale): Collection
    {
        $ids = ContentCache::rememberIds(
            ContentCache::teamMembers($locale),
            fn (): array => TeamMember::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->orderBy('sort_order')
                ->pluck('id')
                ->all(),
        );

        if ($ids === []) {
            return new Collection;
        }

        return TeamMember::query()
            ->whereIn('id', $ids)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->orderBy('sort_order')
            ->get();
    }
}
