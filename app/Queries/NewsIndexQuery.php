<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Enums\PostKind;
use App\Models\Category;
use App\Models\Post;
use App\Services\Seo\SeoMeta;
use App\Support\PublicPath;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class NewsIndexQuery
{
    public function __construct(
        private readonly PostIndex $posts,
    ) {}

    /**
     * @return array{
     *     posts: LengthAwarePaginator<int, Post>,
     *     kind: PostKind|null,
     *     kinds: list<PostKind>,
     *     category_id: int|null,
     *     categories: Collection<int, Category>,
     *     show_filters: bool,
     *     index_url: string,
     *     seo: SeoMeta
     * }
     */
    public function forLocale(string $locale, ?string $kind = null, ?string $category = null): array
    {
        $kindFilter = is_string($kind) && $kind !== ''
            ? PostKind::tryFrom($kind)
            : null;

        $categories = $this->posts->publishedCategoriesForLocale($locale);
        $categoryId = null;

        if (is_string($category) && $category !== '' && ctype_digit($category)) {
            $id = (int) $category;
            $categoryId = $categories->contains(fn (Category $row): bool => $row->id === $id)
                ? $id
                : null;
        }

        $paginator = $this->posts
            ->paginatePublic($locale, $kindFilter, $categoryId)
            ->withQueryString();

        $hasFilters = $kindFilter !== null || $categoryId !== null;
        $path = $locale === Locale::English->value ? 'news' : 'actualites';
        $indexUrl = PublicPath::localized($locale, $path);

        return [
            'posts' => $paginator,
            'kind' => $kindFilter,
            'kinds' => PostKind::cases(),
            'category_id' => $categoryId,
            'categories' => $categories,
            'show_filters' => $paginator->total() > 0 || $hasFilters,
            'index_url' => $indexUrl,
            'seo' => new SeoMeta(
                title: (string) __('ui.news.meta_title', locale: $locale),
                description: (string) __('ui.news.meta_description', locale: $locale),
                canonical: PublicPath::canonical($indexUrl),
                hreflang: [
                    Locale::French->value => PublicPath::canonical(PublicPath::localized(Locale::French->value, 'actualites')),
                    Locale::English->value => PublicPath::canonical(PublicPath::localized(Locale::English->value, 'news')),
                ],
            ),
        ];
    }
}
