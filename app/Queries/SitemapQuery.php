<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\InspectionCentre;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Resource;
use App\Support\ContentCache;
use App\Support\PublicPath;
use Illuminate\Support\Facades\Cache;

final class SitemapQuery
{
    /**
     * @return list<array{loc: string, lastmod: string|null}>
     */
    public function urls(): array
    {
        /** @var list<array{loc: string, lastmod: string|null}> $urls */
        $urls = Cache::remember(
            ContentCache::sitemap(),
            (int) config('cms.cache_ttl', 3600),
            fn (): array => $this->build(),
        );

        return $urls;
    }

    /**
     * @return list<array{loc: string, lastmod: string|null}>
     */
    private function build(): array
    {
        $urls = [];
        $now = now();

        foreach (Locale::codes() as $locale) {
            foreach (Page::query()->publiclyVisible($now)->forLocale($locale)->with('translations')->get() as $page) {
                $this->push($urls, $page, $locale);
            }

            foreach (Product::query()->publiclyVisible($now)->forLocale($locale)->with('translations')->orderBy('sort_order')->get() as $product) {
                $this->push($urls, $product, $locale);
            }

            foreach (Post::query()->publiclyVisible($now)->forLocale($locale)->with('translations')->get() as $post) {
                $this->push($urls, $post, $locale);
            }

            foreach (Resource::query()->publiclyVisible($now)->forLocale($locale)->with('translations')->get() as $resource) {
                $this->push($urls, $resource, $locale);
            }

            foreach (InspectionCentre::query()->publiclyListed()->forLocale($locale)->with('translations')->get() as $centre) {
                $this->push($urls, $centre, $locale);
            }
        }

        return $urls;
    }

    /**
     * @param  list<array{loc: string, lastmod: string|null}>  $urls
     */
    private function push(array &$urls, Page|Product|Post|Resource|InspectionCentre $model, string $locale): void
    {
        $path = PublicPath::for($model, $locale);

        if ($path === null) {
            return;
        }

        $updated = $model->updated_at;

        $urls[] = [
            'loc' => PublicPath::canonical($path),
            'lastmod' => $updated?->toAtomString(),
        ];
    }
}
