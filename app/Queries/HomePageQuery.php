<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\PageCode;
use App\Models\Expertise;
use App\Models\InspectionCentre;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\SecurityPoint;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Collection;

final class HomePageQuery
{
    public function __construct(
        private readonly ProductCatalog $products,
        private readonly ExpertiseCatalog $expertises,
        private readonly CentreDirectory $centres,
        private readonly PostIndex $posts,
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     page: Page|null,
     *     products: Collection<int, Product>,
     *     expertises: Collection<int, Expertise>,
     *     centres: Collection<int, InspectionCentre>,
     *     posts: Collection<int, Post>,
     *     securityPoints: Collection<int, SecurityPoint>,
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
            // Keep the complete active directory available to the homepage map and
            // autocomplete. The template still limits the visible cards to three.
            'centres' => $this->centres->listedForLocale($locale)->values(),
            'posts' => $this->posts->publishedForLocale($locale)->load('media')->take(3)->values(),
            'securityPoints' => SecurityPoint::query()
                ->publiclyVisible(now())
                ->forLocale($locale)
                ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
                ->orderBy('sort_order')
                ->take(12)
                ->get(),
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
