<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\PageCode;
use App\Models\Career;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\PublicPath;
use App\Support\PublicNavigation;

final class CareerShowQuery
{
    public function __construct(
        private readonly CareerCatalog $careers,
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     career: Career,
     *     translation: object,
     *     title: string,
     *     body_html: string|null,
     *     seo: SeoMeta,
     *     breadcrumbs: list<array{label: string, url: string|null}>,
     *     kind_label: string,
     *     is_closed: bool,
     *     closes_on: string|null,
     *     application_url: string|null,
     *     contact_url: string
     * }
     */
    public function forSlug(string $locale, string $slug): array
    {
        $career = $this->careers->findBySlug($locale, $slug);
        $translation = $career->translationFor($locale);

        if ($translation === null) {
            abort(404);
        }

        $title = filled($translation->title)
            ? (string) $translation->title
            : (string) __('ui.career.fallback_title', locale: $locale);

        $kindLabels = __('ui.career.kinds', locale: $locale);
        $kindLabel = is_array($kindLabels)
            ? ($kindLabels[$career->kind->value] ?? $career->kind->value)
            : $career->kind->value;

        $indexPath = PageCode::Careers->slugs()[$locale];
        $isClosed = $career->isClosed();

        return [
            'career' => $career,
            'translation' => $translation,
            'title' => $title,
            'body_html' => $this->bodyHtml($translation->body),
            'seo' => $this->seo->forModel($career, $locale),
            'breadcrumbs' => [
                [
                    'label' => PageCode::About->titles()[$locale],
                    'url' => PublicPath::localized($locale, PageCode::About->slugs()[$locale]),
                ],
                [
                    'label' => PageCode::Careers->titles()[$locale],
                    'url' => PublicPath::localized($locale, $indexPath),
                ],
                [
                    'label' => $title,
                    'url' => null,
                ],
            ],
            'kind_label' => (string) $kindLabel,
            'is_closed' => $isClosed,
            'closes_on' => $career->closes_at !== null
                ? $career->closes_at->translatedFormat('d F Y')
                : null,
            'application_url' => (! $isClosed && filled($career->application_url))
                ? (string) $career->application_url
                : null,
            'contact_url' => collect(PublicNavigation::items($locale))->firstWhere('key', 'contact')['url']
                ?? PublicPath::localized($locale, 'contact'),
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
}
