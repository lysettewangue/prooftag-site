<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\EquipmentLifecycle;
use App\Enums\Locale;
use App\Enums\PageCode;
use App\Models\Equipment;
use App\Models\Page;
use App\Models\SecurityPoint;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\ContentCache;
use App\Support\PublicPath;
use App\Support\TechnicalInspectionVisual;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

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
     *     equipment_items: SupportCollection<int, array{
     *         item: Equipment,
     *         title: string,
     *         body_html: string|null,
     *         lifecycle_label: string,
     *         media_url: string,
     *         href: string|null
     *     }>,
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
            'equipment_items' => $this->equipmentItems($locale),
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

    /**
     * @return SupportCollection<int, array{
     *     item: Equipment,
     *     title: string,
     *     body_html: string|null,
     *     lifecycle_label: string,
     *     media_url: string,
     *     href: string|null
     * }>
     */
    private function equipmentItems(string $locale): SupportCollection
    {
        return $this->equipment->publishedForLocale($locale)->map(function (Equipment $item) use ($locale): array {
            $translation = $item->translationFor($locale);
            $title = filled($translation?->title)
                ? (string) $translation->title
                : (string) __('ui.technical_inspection.equipment_fallback', locale: $locale);

            return [
                'item' => $item,
                'title' => $title,
                'body_html' => $this->bodyHtml($translation?->body),
                'lifecycle_label' => $item->lifecycle === EquipmentLifecycle::Legacy
                    ? (string) __('ui.technical_inspection.lifecycle_legacy', locale: $locale)
                    : (string) __('ui.technical_inspection.lifecycle_current', locale: $locale),
                'media_url' => TechnicalInspectionVisual::equipmentImageFor($item),
                'href' => PublicPath::for($item, $locale),
            ];
        })->values();
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
