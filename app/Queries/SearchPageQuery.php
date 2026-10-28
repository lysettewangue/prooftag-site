<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\InspectionCentre;
use App\Models\Post;
use App\Models\Product;
use App\Models\Resource;
use App\Services\Seo\SeoMeta;
use App\Support\PublicPath;
use Illuminate\Database\Eloquent\Collection;

final class SearchPageQuery
{
    public function __construct(
        private readonly SiteSearch $search,
    ) {}

    /**
     * @return array{
     *     q: string,
     *     submitted: bool,
     *     products: Collection<int, Product>,
     *     posts: Collection<int, Post>,
     *     centres: Collection<int, InspectionCentre>,
     *     resources: Collection<int, Resource>,
     *     total: int,
     *     has_results: bool,
     *     index_url: string,
     *     seo: SeoMeta
     * }
     */
    public function forLocale(string $locale, ?string $q = null): array
    {
        $term = is_string($q) ? trim($q) : '';
        $submitted = $term !== '';
        $results = $this->search->search($locale, $term);
        $total = $results['products']->count()
            + $results['posts']->count()
            + $results['centres']->count()
            + $results['resources']->count();

        $path = $locale === Locale::English->value ? 'search' : 'recherche';
        $indexUrl = PublicPath::localized($locale, $path);

        return [
            'q' => $term,
            'submitted' => $submitted,
            'products' => $results['products'],
            'posts' => $results['posts'],
            'centres' => $results['centres'],
            'resources' => $results['resources'],
            'total' => $total,
            'has_results' => $total > 0,
            'index_url' => $indexUrl,
            'seo' => new SeoMeta(
                title: (string) __('ui.search.meta_title', locale: $locale),
                description: (string) __('ui.search.meta_description', locale: $locale),
                canonical: PublicPath::canonical($indexUrl),
                hreflang: [
                    Locale::French->value => PublicPath::canonical(PublicPath::localized(Locale::French->value, 'recherche')),
                    Locale::English->value => PublicPath::canonical(PublicPath::localized(Locale::English->value, 'search')),
                ],
            ),
        ];
    }
}
