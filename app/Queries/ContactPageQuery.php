<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Enums\PageCode;
use App\Models\Page;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\ContentCache;
use App\Support\PublicFooter;
use App\Support\PublicPath;

final class ContactPageQuery
{
    public function __construct(
        private readonly SeoMetaResolver $seo,
        private readonly PublicFooter $footer,
    ) {}

    /**
     * @return array{
     *     page: Page,
     *     title: string,
     *     blocks: list<array<string, mixed>>,
     *     seo: SeoMeta,
     *     breadcrumbs: list<array{label: string, url: string|null}>,
     *     identity: array{
     *         legal_name: string,
     *         building: string|null,
     *         postal_line: string|null,
     *         telephone: string|null,
     *         telephone_uri: string|null,
     *         email: string|null
     *     },
     *     form_action: string,
     *     sent: bool
     * }
     */
    public function forLocale(string $locale): array
    {
        $page = $this->publishedPage($locale);

        if (! $page instanceof Page) {
            abort(404);
        }

        $translation = $page->translationFor($locale);
        $title = is_object($translation) && filled($translation->title)
            ? (string) $translation->title
            : PageCode::Contact->titles()[$locale];
        /** @var list<array<string, mixed>> $blocks */
        $blocks = is_object($translation) && is_array($translation->blocks)
            ? $translation->blocks
            : [];

        $footer = $this->footer->forLocale($locale);
        $formAction = route(
            $locale === Locale::English->value ? 'en.contact.store' : 'fr.contact.store',
        );

        return [
            'page' => $page,
            'title' => $title,
            'blocks' => $blocks,
            'seo' => $this->seo->forModel($page, $locale),
            'breadcrumbs' => [
                ['label' => $title, 'url' => null],
            ],
            'identity' => [
                'legal_name' => $footer['legal_name'],
                'building' => $footer['building'],
                'postal_line' => $footer['postal_line'],
                'telephone' => $footer['telephone'],
                'telephone_uri' => $footer['telephone_uri'],
                'email' => $footer['email'],
            ],
            'form_action' => $formAction,
            'sent' => session()->pull('contact_sent') === true,
        ];
    }

    private function publishedPage(string $locale): ?Page
    {
        $id = ContentCache::rememberNullableId(
            ContentCache::page(PageCode::Contact->value, $locale),
            function () use ($locale): ?int {
                $id = Page::query()
                    ->where('code', PageCode::Contact)
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
