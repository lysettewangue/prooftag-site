<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\Career;
use App\Models\Equipment;
use App\Models\Expertise;
use App\Models\InspectionCentre;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Resource;
use App\Support\ContentCache;
use App\Support\PublicPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

final class SitemapQuery
{
    /**
     * @return list<array{loc: string, lastmod: string|null, alternates: array<string, string>}>
     */
    public function urls(): array
    {
        /** @var list<array{loc: string, lastmod: string|null, alternates: array<string, string>}> $urls */
        $urls = Cache::remember(
            ContentCache::sitemap(),
            (int) config('cms.cache_ttl', 3600),
            fn (): array => $this->build(),
        );

        return $urls;
    }

    /**
     * @return list<array{loc: string, lastmod: string|null, alternates: array<string, string>}>
     */
    private function build(): array
    {
        /** @var array<string, array{loc: string, lastmod: string|null, alternates: array<string, string>}> $byLoc */
        $byLoc = [];
        $now = now();

        $this->pushIndex($byLoc, '/', '/');
        $this->pushIndex($byLoc, 'produits', 'products');
        $this->pushIndex($byLoc, 'expertises', 'expertise');
        $this->pushIndex($byLoc, 'controle-technique/points-de-securisation', 'technical-inspection/security-points');
        $this->pushIndex($byLoc, 'controle-technique/equipements', 'technical-inspection/equipment');
        $this->pushIndex($byLoc, 'centres-de-controle', 'inspection-centres');
        $this->pushIndex($byLoc, 'ressources', 'resources');
        $this->pushIndex($byLoc, 'actualites', 'news');
        $this->pushIndex($byLoc, 'recherche', 'search');

        foreach (Locale::codes() as $locale) {
            foreach (Page::query()->publiclyVisible($now)->forLocale($locale)->with('translations')->get() as $page) {
                $this->pushModel($byLoc, $page, $locale);
            }

            foreach (Product::query()->publiclyVisible($now)->forLocale($locale)->with('translations')->orderBy('sort_order')->get() as $product) {
                $this->pushModel($byLoc, $product, $locale);
            }

            foreach (Expertise::query()->publiclyVisible($now)->forLocale($locale)->with('translations')->orderBy('sort_order')->get() as $expertise) {
                $this->pushModel($byLoc, $expertise, $locale);
            }

            foreach (Equipment::query()->publiclyVisible($now)->forLocale($locale)->with('translations')->get() as $equipment) {
                $this->pushModel($byLoc, $equipment, $locale);
            }

            foreach (Post::query()->publiclyVisible($now)->forLocale($locale)->with('translations')->get() as $post) {
                $this->pushModel($byLoc, $post, $locale);
            }

            foreach (Resource::query()->publiclyVisible($now)->forLocale($locale)->with('translations')->get() as $resource) {
                $this->pushModel($byLoc, $resource, $locale);
            }

            foreach (Career::query()->publiclyVisible($now)->forLocale($locale)->with('translations')->get() as $career) {
                $this->pushModel($byLoc, $career, $locale);
            }

            foreach (InspectionCentre::query()->publiclyListed()->forLocale($locale)->with('translations')->get() as $centre) {
                $this->pushModel($byLoc, $centre, $locale);
            }
        }

        return array_values($byLoc);
    }

    /**
     * @param  array<string, array{loc: string, lastmod: string|null, alternates: array<string, string>}>  $byLoc
     */
    private function pushIndex(array &$byLoc, string $frPath, string $enPath): void
    {
        $alternates = [
            Locale::French->value => PublicPath::canonical(PublicPath::localized(Locale::French->value, $frPath)),
            Locale::English->value => PublicPath::canonical(PublicPath::localized(Locale::English->value, $enPath)),
        ];

        foreach ([
            Locale::French->value => $frPath,
            Locale::English->value => $enPath,
        ] as $locale => $path) {
            $loc = PublicPath::canonical(PublicPath::localized($locale, $path));
            $byLoc[$loc] = [
                'loc' => $loc,
                'lastmod' => null,
                'alternates' => $alternates,
            ];
        }
    }

    /**
     * @param  array<string, array{loc: string, lastmod: string|null, alternates: array<string, string>}>  $byLoc
     */
    private function pushModel(array &$byLoc, Model $model, string $locale): void
    {
        $path = PublicPath::for($model, $locale);

        if ($path === null) {
            return;
        }

        $loc = PublicPath::canonical($path);
        $updated = $model->getAttribute('updated_at');
        $lastmod = is_object($updated) && method_exists($updated, 'toAtomString')
            ? $updated->toAtomString()
            : null;

        $byLoc[$loc] = [
            'loc' => $loc,
            'lastmod' => $lastmod,
            'alternates' => $this->alternatesFor($model),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function alternatesFor(Model $model): array
    {
        $pairs = [];

        foreach (Locale::codes() as $locale) {
            if (method_exists($model, 'hasLocale') && ! $model->hasLocale($locale)) {
                continue;
            }

            $path = PublicPath::for($model, $locale);

            if ($path === null) {
                continue;
            }

            $pairs[$locale] = PublicPath::canonical($path);
        }

        return $pairs;
    }
}
