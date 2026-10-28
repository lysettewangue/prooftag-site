<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\PostKind;
use App\Models\Category;
use App\Models\Post;
use App\Support\ContentCache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class PostIndex
{
    /**
     * @return Collection<int, Post>
     */
    public function publishedForLocale(string $locale): Collection
    {
        $ids = ContentCache::rememberIds(
            ContentCache::posts($locale),
            fn (): array => Post::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->latest('published_at')
                ->pluck('id')
                ->all(),
        );

        if ($ids === []) {
            return new Collection;
        }

        return Post::query()
            ->whereIn('id', $ids)
            ->with([
                'translations' => fn ($query) => $query->where('locale', $locale),
                'categories' => fn ($query) => $query->publiclyVisible(now())->forLocale($locale),
                'categories.translations' => fn ($query) => $query->where('locale', $locale),
            ])
            ->latest('published_at')
            ->get();
    }

    /**
     * @return Collection<int, Category>
     */
    public function publishedCategoriesForLocale(string $locale): Collection
    {
        return Category::query()
            ->publiclyVisible(now())
            ->forLocale($locale)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * @return LengthAwarePaginator<int, Post>
     */
    public function paginatePublic(
        string $locale,
        ?PostKind $kind = null,
        ?int $categoryId = null,
        int $perPage = 15,
    ): LengthAwarePaginator {
        $query = Post::query()
            ->publiclyVisible(now())
            ->forLocale($locale)
            ->with([
                'translations' => fn ($q) => $q->where('locale', $locale),
                'categories' => fn ($q) => $q->publiclyVisible(now())->forLocale($locale),
                'categories.translations' => fn ($q) => $q->where('locale', $locale),
            ]);

        if ($kind !== null) {
            $query->where('kind', $kind);
        }

        if ($categoryId !== null) {
            $query->whereHas(
                'categories',
                fn ($q) => $q->whereKey($categoryId)->publiclyVisible(now())->forLocale($locale),
            );
        }

        return $query->latest('published_at')->paginate($perPage);
    }

    public function findBySlug(string $locale, string $slug): Post
    {
        return Post::query()
            ->publiclyVisible(now())
            ->withLocaleSlug($locale, $slug)
            ->with([
                'translations' => fn ($query) => $query->where('locale', $locale),
                'media',
                'categories' => fn ($query) => $query->publiclyVisible(now())->forLocale($locale),
                'categories.translations' => fn ($query) => $query->where('locale', $locale),
                'products' => fn ($query) => $query
                    ->publiclyVisible(now())
                    ->forLocale($locale)
                    ->orderBy('sort_order')
                    ->with(['translations' => fn ($translations) => $translations->where('locale', $locale)]),
                'expertises' => fn ($query) => $query
                    ->publiclyVisible(now())
                    ->forLocale($locale)
                    ->orderBy('sort_order')
                    ->with(['translations' => fn ($translations) => $translations->where('locale', $locale)]),
            ])
            ->firstOrFail();
    }
}
