<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\PostKind;
use App\Models\Post;
use App\Support\ContentCache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class PostIndex
{
    /**
     * @return Collection<int, Post>
     */
    public function publishedForLocale(string $locale): Collection
    {
        /** @var Collection<int, Post> $posts */
        $posts = Cache::remember(
            ContentCache::posts($locale),
            (int) config('cms.cache_ttl', 3600),
            function () use ($locale): Collection {
                return Post::query()
                    ->publiclyVisible(now())
                    ->forLocale($locale)
                    ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
                    ->latest('published_at')
                    ->get();
            },
        );

        return $posts;
    }

    /**
     * @return LengthAwarePaginator<int, Post>
     */
    public function paginatePublic(string $locale, ?PostKind $kind = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Post::query()
            ->publiclyVisible(now())
            ->forLocale($locale)
            ->with(['translations' => fn ($q) => $q->where('locale', $locale), 'categories']);

        if ($kind !== null) {
            $query->where('kind', $kind);
        }

        return $query->latest('published_at')->paginate($perPage);
    }

    public function findBySlug(string $locale, string $slug): Post
    {
        return Post::query()
            ->publiclyVisible(now())
            ->withLocaleSlug($locale, $slug)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale), 'categories', 'products'])
            ->firstOrFail();
    }
}
