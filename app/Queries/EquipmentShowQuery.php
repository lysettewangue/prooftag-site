<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\EquipmentLifecycle;
use App\Enums\Locale;
use App\Models\Equipment;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\PublicPath;

final class EquipmentShowQuery
{
    public function __construct(
        private readonly EquipmentCatalog $equipment,
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     item: Equipment,
     *     translation: object,
     *     title: string,
     *     body_html: string|null,
     *     lifecycle_label: string,
     *     seo: SeoMeta,
     *     breadcrumbs: list<array{label: string, url: string|null}>,
     *     media_url: string|null,
     *     media_alt: string|null
     * }
     */
    public function forSlug(string $locale, string $slug): array
    {
        $item = $this->equipment->findBySlug($locale, $slug);
        $translation = $item->translationFor($locale);

        if ($translation === null) {
            abort(404);
        }

        $title = filled($translation->title)
            ? (string) $translation->title
            : (string) __('ui.equipment.fallback_title', locale: $locale);

        $indexPath = $locale === Locale::English->value
            ? 'technical-inspection/equipment'
            : 'controle-technique/equipements';
        $hubPath = $locale === Locale::English->value
            ? 'technical-inspection'
            : 'controle-technique';
        $media = $item->getFirstMedia('images');

        return [
            'item' => $item,
            'translation' => $translation,
            'title' => $title,
            'body_html' => $this->bodyHtml($translation->body),
            'lifecycle_label' => $item->lifecycle === EquipmentLifecycle::Legacy
                ? (string) __('ui.equipment.lifecycle_legacy', locale: $locale)
                : (string) __('ui.equipment.lifecycle_current', locale: $locale),
            'seo' => $this->seo->forModel($item, $locale),
            'breadcrumbs' => [
                [
                    'label' => (string) __('ui.navigation.items.technical_inspection', locale: $locale),
                    'url' => PublicPath::localized($locale, $hubPath),
                ],
                [
                    'label' => (string) __('ui.equipment_index.title', locale: $locale),
                    'url' => PublicPath::localized($locale, $indexPath),
                ],
                [
                    'label' => $title,
                    'url' => null,
                ],
            ],
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
