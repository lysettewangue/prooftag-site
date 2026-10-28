<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\Expertise;
use App\Models\Product;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
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
     *     seo: SeoMeta,
     *     breadcrumbs: list<array{label: string, url: string|null}>,
     *     products: Collection<int, Product>
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

        $indexPath = $locale === Locale::English->value ? 'expertise' : 'expertises';
        $indexLabel = (string) __('ui.navigation.items.expertise', locale: $locale);

        return [
            'expertise' => $expertise,
            'translation' => $translation,
            'title' => $title,
            'body_html' => $this->bodyHtml($translation->body),
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
