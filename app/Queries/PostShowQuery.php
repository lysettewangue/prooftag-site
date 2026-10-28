<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\Expertise;
use App\Models\Post;
use App\Models\Product;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\PublicPath;
use Illuminate\Database\Eloquent\Collection;

final class PostShowQuery
{
    public function __construct(
        private readonly PostIndex $posts,
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     post: Post,
     *     translation: object,
     *     title: string,
     *     body_html: string|null,
     *     seo: SeoMeta,
     *     breadcrumbs: list<array{label: string, url: string|null}>,
     *     meta_parts: list<string>,
     *     products: Collection<int, Product>,
     *     expertises: Collection<int, Expertise>,
     *     media_url: string|null,
     *     media_alt: string|null
     * }
     */
    public function forSlug(string $locale, string $slug): array
    {
        $post = $this->posts->findBySlug($locale, $slug);
        $translation = $post->translationFor($locale);

        if ($translation === null) {
            abort(404);
        }

        $title = filled($translation->title)
            ? (string) $translation->title
            : (string) __('ui.article.fallback_title', locale: $locale);

        $indexPath = $locale === Locale::English->value ? 'news' : 'actualites';
        $kindLabels = __('ui.news.kinds', locale: $locale);
        $kindText = is_array($kindLabels)
            ? ($kindLabels[$post->kind->value] ?? $post->kind->value)
            : $post->kind->value;

        $categoryNames = $post->categories
            ->map(fn ($category) => $category->translationFor($locale)?->name)
            ->filter()
            ->values();

        $metaParts = collect([
            $categoryNames->implode(', ') ?: null,
            $kindText,
            $post->published_at?->translatedFormat('d F Y'),
        ])->filter()->values()->all();

        $media = $post->getFirstMedia('images');

        return [
            'post' => $post,
            'translation' => $translation,
            'title' => $title,
            'body_html' => $this->bodyHtml($translation->body),
            'seo' => $this->seo->forModel($post, $locale),
            'breadcrumbs' => [
                [
                    'label' => (string) __('ui.navigation.items.news', locale: $locale),
                    'url' => PublicPath::localized($locale, $indexPath),
                ],
                [
                    'label' => $title,
                    'url' => null,
                ],
            ],
            'meta_parts' => $metaParts,
            'products' => $post->products,
            'expertises' => $post->expertises,
            'media_url' => $media?->getUrl(),
            'media_alt' => $this->mediaAlt($media?->getCustomProperty('alt'), $locale, $title),
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
