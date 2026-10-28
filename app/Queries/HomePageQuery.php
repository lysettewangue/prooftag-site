<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\PageCode;
use App\Models\Expertise;
use App\Models\Page;
use App\Models\Product;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Collection;

final class HomePageQuery
{
    public function __construct(
        private readonly ProductCatalog $products,
        private readonly ExpertiseCatalog $expertises,
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     page: Page|null,
     *     products: Collection<int, Product>,
     *     expertises: Collection<int, Expertise>,
     *     seo: SeoMeta
     * }
     */
    public function forLocale(string $locale): array
    {
        $page = $this->publishedHome($locale);
        $resolved = $page instanceof Page
            ? $this->seo->forModel($page, $locale)
            : $this->seo->forPage($locale, '/');

        return [
            'page' => $page,
            'products' => $this->products->publishedForLocale($locale)->take(5)->values(),
            'expertises' => $this->expertises->publishedForLocale($locale),
            'seo' => $this->documentSeo($locale, $page, $resolved),
        ];
    }

    private function publishedHome(string $locale): ?Page
    {
        $id = ContentCache::rememberNullableId(
            ContentCache::homePage($locale),
            function () use ($locale): ?int {
                $id = Page::query()
                    ->where('code', PageCode::Home)
                    ->publiclyVisible(now())
                    ->forLocale($locale)
                    ->value('id');

                return $id !== null ? (int) $id : null;
            },
        );

        if ($id === null) {
            return null;
        }

        return Page::query()
            ->whereKey($id)
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->first();
    }

    private function documentSeo(string $locale, ?Page $page, SeoMeta $resolved): SeoMeta
    {
        $translation = $page?->translationFor($locale);
        $title = is_object($translation) && filled($translation->meta_title)
            ? $resolved->title
            : __('ui.home_heading', locale: $locale).' — '.config('app.name');
        $description = is_object($translation) && filled($translation->meta_description)
            ? $resolved->description
            : (string) __('ui.home_body', locale: $locale);

        return new SeoMeta(
            title: $title,
            description: $description,
            canonical: $resolved->canonical,
            hreflang: $resolved->hreflang !== []
                ? $resolved->hreflang
                : [
                    'fr' => locale_route('home', locale: 'fr'),
                    'en' => locale_route('home', locale: 'en'),
                ],
            image: $resolved->image,
            type: $resolved->type,
        );
    }
}
