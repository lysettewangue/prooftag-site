<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\Resource;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\PublicPath;

final class ResourceShowQuery
{
    public function __construct(
        private readonly ResourceLibrary $library,
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     resource: Resource,
     *     translation: object,
     *     title: string,
     *     seo: SeoMeta,
     *     breadcrumbs: list<array{label: string, url: string|null}>,
     *     has_document: bool,
     *     download_url: string|null
     * }
     */
    public function forSlug(string $locale, string $slug): array
    {
        $resource = $this->library->findBySlug($locale, $slug);
        $translation = $resource->translationFor($locale);

        if ($translation === null) {
            abort(404);
        }

        $title = filled($translation->title)
            ? (string) $translation->title
            : (string) __('ui.resource.fallback_title', locale: $locale);

        $indexPath = $locale === Locale::English->value ? 'resources' : 'ressources';
        $hasDocument = $resource->getFirstMedia('documents') !== null;

        return [
            'resource' => $resource,
            'translation' => $translation,
            'title' => $title,
            'seo' => $this->seo->forModel($resource, $locale),
            'breadcrumbs' => [
                [
                    'label' => (string) __('ui.navigation.items.resources', locale: $locale),
                    'url' => PublicPath::localized($locale, $indexPath),
                ],
                [
                    'label' => $title,
                    'url' => null,
                ],
            ],
            'has_document' => $hasDocument,
            'download_url' => $hasDocument
                ? route($locale === Locale::English->value ? 'en.resources.download' : 'fr.resources.download', ['slug' => $slug])
                : null,
        ];
    }
}
