<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\PageCode;
use App\Models\Career;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Product;
use App\Models\TeamMember;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\ContentCache;
use App\Support\PublicPath;
use Illuminate\Database\Eloquent\Collection;

final class CorporatePageQuery
{
    /**
     * @var list<PageCode>
     */
    public const ABOUT_SECTION = [
        PageCode::About,
        PageCode::Organisation,
        PageCode::History,
        PageCode::Team,
        PageCode::Partners,
        PageCode::Careers,
    ];

    public function __construct(
        private readonly SeoMetaResolver $seo,
        private readonly TeamCatalog $team,
        private readonly PartnerCatalog $partners,
        private readonly CareerCatalog $careers,
        private readonly ProductCatalog $products,
    ) {}

    /**
     * @return array{
     *     page: Page,
     *     code: PageCode,
     *     title: string,
     *     blocks: list<array<string, mixed>>,
     *     seo: SeoMeta,
     *     breadcrumbs: list<array{label: string, url: string|null}>,
     *     section_links: list<array{label: string, url: string, active: bool}>,
     *     members: Collection<int, TeamMember>,
     *     partners: Collection<int, Partner>,
     *     careers: Collection<int, Career>,
     *     related_products: Collection<int, Product>
     * }
     */
    public function forCode(PageCode $code, string $locale): array
    {
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
            'breadcrumbs' => $this->breadcrumbs($code, $locale, $title),
            'section_links' => $this->sectionLinks($locale, $code),
            'members' => $code === PageCode::Team
                ? $this->team->publishedForLocale($locale)
                : new Collection,
            'partners' => $code === PageCode::Partners
                ? $this->partners->publishedForLocale($locale)
                : new Collection,
            'careers' => $code === PageCode::Careers
                ? $this->careers->publishedForLocale($locale)
                : new Collection,
            'related_products' => $this->products->publishedForLocale($locale),
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

    /**
     * @return list<array{label: string, url: string|null}>
     */
    private function breadcrumbs(PageCode $code, string $locale, string $title): array
    {
        if ($code === PageCode::About) {
            return [
                ['label' => $title, 'url' => null],
            ];
        }

        $about = $this->publishedPage(PageCode::About, $locale);
        $aboutTitle = $about?->translationFor($locale)?->title
            ?: PageCode::About->titles()[$locale];
        $aboutUrl = $about instanceof Page
            ? PublicPath::for($about, $locale)
            : PublicPath::localized($locale, PageCode::About->slugs()[$locale]);

        return [
            ['label' => (string) $aboutTitle, 'url' => $aboutUrl],
            ['label' => $title, 'url' => null],
        ];
    }

    /**
     * @return list<array{label: string, url: string, active: bool}>
     */
    private function sectionLinks(string $locale, PageCode $current): array
    {
        $links = [];

        foreach (self::ABOUT_SECTION as $code) {
            if ($code === PageCode::About) {
                continue;
            }

            $page = $this->publishedPage($code, $locale);

            if (! $page instanceof Page) {
                continue;
            }

            $url = PublicPath::for($page, $locale);

            if ($url === null) {
                continue;
            }

            $label = $page->translationFor($locale)?->title ?: $code->titles()[$locale];

            $links[] = [
                'label' => (string) $label,
                'url' => $url,
                'active' => $code === $current,
            ];
        }

        return $links;
    }
}
