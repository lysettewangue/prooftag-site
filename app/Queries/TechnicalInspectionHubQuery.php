<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Enums\PageCode;
use App\Models\Equipment;
use App\Models\Page;
use App\Models\SecurityPoint;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\ContentCache;
use App\Support\PublicPath;
use Illuminate\Database\Eloquent\Collection;

final class TechnicalInspectionHubQuery
{
    public function __construct(
        private readonly SeoMetaResolver $seo,
        private readonly SecurityPointCatalog $securityPoints,
        private readonly EquipmentCatalog $equipment,
    ) {}

    /**
     * @return array{
     *     page: Page,
     *     title: string,
     *     blocks: list<array<string, mixed>>,
     *     seo: SeoMeta,
     *     security_points: Collection<int, SecurityPoint>,
     *     equipment_preview: Collection<int, Equipment>,
     *     points_url: string,
     *     equipment_url: string
     * }
     */
    public function forLocale(string $locale): array
    {
        $page = $this->publishedHub($locale);

        if (! $page instanceof Page) {
            abort(404);
        }

        $translation = $page->translationFor($locale);
        $title = is_object($translation) && filled($translation->title)
            ? (string) $translation->title
            : PageCode::TechnicalInspection->titles()[$locale];
        /** @var list<array<string, mixed>> $blocks */
        $blocks = is_object($translation) && is_array($translation->blocks)
            ? $translation->blocks
            : [];

        return [
            'page' => $page,
            'title' => $title,
            'blocks' => $blocks,
            'seo' => $this->seo->forModel($page, $locale),
            'security_points' => $this->securityPoints->publishedForLocale($locale),
            'equipment_preview' => $this->equipment->publishedForLocale($locale)->take(4)->values(),
            'points_url' => PublicPath::localized(
                $locale,
                $locale === Locale::English->value
                    ? 'technical-inspection/security-points'
                    : 'controle-technique/points-de-securisation',
            ),
            'equipment_url' => PublicPath::localized(
                $locale,
                $locale === Locale::English->value
                    ? 'technical-inspection/equipment'
                    : 'controle-technique/equipements',
            ),
        ];
    }

    private function publishedHub(string $locale): ?Page
    {
        $id = ContentCache::rememberNullableId(
            ContentCache::page(PageCode::TechnicalInspection->value, $locale),
            function () use ($locale): ?int {
                $id = Page::query()
                    ->where('code', PageCode::TechnicalInspection)
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
