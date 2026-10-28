<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\PageCode;
use App\Models\Page;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\ContentCache;

final class LegalPageQuery
{
    /**
     * @var list<PageCode>
     */
    public const CODES = [
        PageCode::LegalNotice,
        PageCode::Privacy,
    ];

    public function __construct(
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     page: Page,
     *     code: PageCode,
     *     title: string,
     *     blocks: list<array<string, mixed>>,
     *     seo: SeoMeta,
     *     breadcrumbs: list<array{label: string, url: string|null}>
     * }
     */
    public function forCode(PageCode $code, string $locale): array
    {
        if (! in_array($code, self::CODES, true)) {
            abort(404);
        }

        $page = $this->publishedPage($code, $locale);

        if (! $page instanceof Page) {
            abort(404);
        }

        $translation = $page->translationFor($locale);
        $title = is_object($translation) && filled($translation->title)
            ? (string) $translation->title
            : $code->titles()[$locale];
        /** @var list<array<string, mixed>> $blocks */
        $blocks = is_object($translation) && is_array($translation->blocks)
            ? $translation->blocks
            : [];

        return [
            'page' => $page,
            'code' => $code,
            'title' => $title,
            'blocks' => $blocks,
            'seo' => $this->seo->forModel($page, $locale),
            'breadcrumbs' => [
                ['label' => $title, 'url' => null],
            ],
        ];
    }

    private function publishedPage(PageCode $code, string $locale): ?Page
    {
        $id = ContentCache::rememberNullableId(
            ContentCache::page($code->value, $locale),
            function () use ($code, $locale): ?int {
                $id = Page::query()
                    ->where('code', $code)
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
}
