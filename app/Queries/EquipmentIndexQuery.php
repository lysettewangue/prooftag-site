<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\Equipment;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\PublicPath;
use Illuminate\Database\Eloquent\Collection;

final class EquipmentIndexQuery
{
    public function __construct(
        private readonly EquipmentCatalog $equipment,
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     equipment: Collection<int, Equipment>,
     *     seo: SeoMeta,
     *     breadcrumbs: list<array{label: string, url: string|null}>,
     *     hub_url: string
     * }
     */
    public function forLocale(string $locale): array
    {
        $hubPath = $locale === Locale::English->value
            ? 'technical-inspection'
            : 'controle-technique';
        $resolved = $this->seo->forLocalizedIndex(
            $locale,
            'controle-technique/equipements',
            'technical-inspection/equipment',
        );
        $hubUrl = PublicPath::localized($locale, $hubPath);

        return [
            'equipment' => $this->equipment->publishedForLocale($locale),
            'hub_url' => $hubUrl,
            'seo' => new SeoMeta(
                title: (string) __('ui.equipment_index.meta_title', locale: $locale),
                description: (string) __('ui.equipment_index.meta_description', locale: $locale),
                canonical: $resolved->canonical,
                hreflang: $resolved->hreflang,
                image: $resolved->image,
                type: $resolved->type,
            ),
            'breadcrumbs' => [
                [
                    'label' => (string) __('ui.navigation.items.technical_inspection', locale: $locale),
                    'url' => $hubUrl,
                ],
                [
                    'label' => (string) __('ui.equipment_index.title', locale: $locale),
                    'url' => null,
                ],
            ],
        ];
    }
}
