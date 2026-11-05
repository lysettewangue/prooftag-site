<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\Expertise;
use App\Models\Product;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\ExpertiseVisual;
use App\Support\PublicEditorialCopy;
use App\Support\PublicPath;
use Illuminate\Database\Eloquent\Collection;

final class ExpertiseShowQuery
{
    public function __construct(
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     expertise: Expertise,
     *     translation: object,
     *     title: string,
     *     body_html: string|null,
     *     summary: string|null,
     *     paragraphs: list<string>,
     *     highlights: list<string>,
     *     media_url: string,
     *     media_icon: string,
     *     seo: SeoMeta,
     *     breadcrumbs: list<array{label: string, url: string|null}>,
     *     products: Collection<int, Product>,
     *     siblings: Collection<int, Expertise>
     * }
     */
    public function forSlug(string $locale, string $slug): array
    {
        $expertise = Expertise::query()
            ->publiclyVisible(now())
            ->withLocaleSlug($locale, $slug)
            ->with([
                'translations' => fn ($query) => $query->where('locale', $locale),
                'products' => fn ($query) => $query
                    ->publiclyVisible(now())
                    ->forLocale($locale)
                    ->orderBy('sort_order')
                    ->with(['translations' => fn ($translations) => $translations->where('locale', $locale)]),
            ])
            ->firstOrFail();

        $translation = $expertise->translationFor($locale);

        if ($translation === null) {
            abort(404);
        }

        $title = filled($translation->title)
            ? (string) $translation->title
            : $expertise->code->titles()[$locale];

        $rawBody = $this->bodyHtml($translation->body);
        $bodyHtml = PublicEditorialCopy::html($rawBody);
        $paragraphs = PublicEditorialCopy::paragraphs($bodyHtml);
        $highlights = PublicEditorialCopy::listItems($bodyHtml);
        $summary = PublicEditorialCopy::summary(
            is_string($translation->summary) ? $translation->summary : null
        ) ?? ($paragraphs[0] ?? null);

        $indexPath = $locale === Locale::English->value ? 'expertise' : 'expertises';
        $indexLabel = (string) __('ui.navigation.items.expertise', locale: $locale);

        $siblings = Expertise::query()
            ->publiclyVisible(now())
            ->forLocale($locale)
            ->whereKeyNot($expertise->getKey())
            ->orderBy('sort_order')
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->get();

        return [
            'expertise' => $expertise,
            'translation' => $translation,
            'title' => $title,
            'body_html' => $bodyHtml,
            'summary' => $summary,
            'paragraphs' => $paragraphs,
            'highlights' => $highlights,
            'media_url' => ExpertiseVisual::imageFor($expertise),
            'media_icon' => ExpertiseVisual::iconFor($expertise),
            'seo' => $this->seo->forModel($expertise, $locale),
            'breadcrumbs' => [
                [
                    'label' => $indexLabel,
                    'url' => PublicPath::localized($locale, $indexPath),
                ],
                [
                    'label' => $title,
                    'url' => null,
                ],
            ],
            'products' => $expertise->products,
            'siblings' => $siblings,
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
