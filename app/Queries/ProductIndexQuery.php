<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\Product;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use Illuminate\Database\Eloquent\Collection;

final class ProductIndexQuery
{
    public function __construct(
        private readonly ProductCatalog $products,
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     products: Collection<int, Product>,
     *     seo: SeoMeta,
     *     index_path: string
     * }
     */
    public function forLocale(string $locale): array
    {
        $path = $locale === Locale::English->value ? 'products' : 'produits';
        $resolved = $this->seo->forLocalizedIndex($locale, 'produits', 'products');

        return [
            'products' => $this->products->publishedForLocale($locale),
            'index_path' => $path,
            'seo' => new SeoMeta(
                title: (string) __('ui.products.meta_title', locale: $locale),
                description: (string) __('ui.products.meta_description', locale: $locale),
                canonical: $resolved->canonical,
                hreflang: $resolved->hreflang,
                image: $resolved->image,
                type: $resolved->type,
            ),
        ];
    }
}
