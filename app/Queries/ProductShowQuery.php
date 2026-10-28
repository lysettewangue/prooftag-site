<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\Expertise;
use App\Models\Product;
use App\Models\Resource;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\PublicPath;
use Illuminate\Database\Eloquent\Collection;

final class ProductShowQuery
{
    public function __construct(
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     product: Product,
     *     translation: object,
     *     body_html: string|null,
     *     seo: SeoMeta,
     *     breadcrumbs: list<array{label: string, url: string|null}>,
     *     expertises: Collection<int, Expertise>,
     *     resources: Collection<int, Resource>,
     *     media_url: string|null,
     *     media_alt: string|null
     * }
     */
    public function forSlug(string $locale, string $slug): array
    {
        $product = Product::query()
            ->publiclyVisible(now())
            ->withLocaleSlug($locale, $slug)
            ->with([
                'translations' => fn ($query) => $query->where('locale', $locale),
                'media',
                'expertises' => fn ($query) => $query
                    ->publiclyVisible(now())
                    ->forLocale($locale)
                    ->orderBy('sort_order')
                    ->with(['translations' => fn ($translations) => $translations->where('locale', $locale)]),
                'resources' => fn ($query) => $query
                    ->publiclyVisible(now())
                    ->forLocale($locale)
                    ->latest('published_at')
                    ->with(['translations' => fn ($translations) => $translations->where('locale', $locale)]),
            ])
            ->firstOrFail();

        $translation = $product->translationFor($locale);

        if ($translation === null) {
            abort(404);
        }

        $indexPath = $locale === Locale::English->value ? 'products' : 'produits';
        $indexLabel = (string) __('ui.navigation.items.products', locale: $locale);
        $media = $product->getFirstMedia('images');

        return [
            'product' => $product,
            'translation' => $translation,
            'body_html' => $this->bodyHtml($translation->body),
            'seo' => $this->seo->forModel($product, $locale),
            'breadcrumbs' => [
                [
                    'label' => $indexLabel,
                    'url' => PublicPath::localized($locale, $indexPath),
                ],
                [
                    'label' => $product->public_name,
                    'url' => null,
                ],
            ],
            'expertises' => $product->expertises,
            'resources' => $product->resources,
            'media_url' => $media?->getUrl(),
            'media_alt' => $this->mediaAlt($media?->getCustomProperty('alt'), $locale, $product->public_name),
        ];
    }

    private function bodyHtml(mixed $body): ?string
    {
        if (is_array($body)) {
            $html = $body['html'] ?? null;

            return is_string($html) && $html !== '' ? $html : null;
        }

        if (is_string($body) && $body !== '') {
            return $body;
        }

        return null;
    }

    private function mediaAlt(mixed $alt, string $locale, string $fallback): string
    {
        if (is_array($alt)) {
            $value = $alt[$locale] ?? $alt['fr'] ?? $alt['en'] ?? null;

            return is_string($value) && $value !== '' ? $value : $fallback;
        }

        return is_string($alt) && $alt !== '' ? $alt : $fallback;
    }
}
