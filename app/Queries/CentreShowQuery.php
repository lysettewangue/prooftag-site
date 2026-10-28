<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\InspectionCentre;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\PublicPath;

final class CentreShowQuery
{
    public function __construct(
        private readonly CentreDirectory $directory,
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     centre: InspectionCentre,
     *     translation: object,
     *     name: string,
     *     place: string,
     *     seo: SeoMeta,
     *     breadcrumbs: list<array{label: string, url: string|null}>,
     *     markers: list<array{name: string, city: string, lat: float, lng: float, url: string|null}>,
     *     missing_notes: list<string>
     * }
     */
    public function forSlug(string $locale, string $slug): array
    {
        $centre = $this->directory->findBySlug($locale, $slug);
        $translation = $centre->translationFor($locale);

        if ($translation === null) {
            abort(404);
        }

        $name = filled($translation->name)
            ? (string) $translation->name
            : (string) $centre->operator_name;

        $regionName = $centre->region?->translationFor($locale)?->name;
        $place = collect([(string) $centre->city, $regionName])->filter()->implode(' · ');

        $indexPath = $locale === Locale::English->value
            ? 'inspection-centres'
            : 'centres-de-controle';

        $markers = [];

        if ($centre->latitude !== null && $centre->longitude !== null) {
            $markers[] = [
                'name' => $name,
                'city' => (string) $centre->city,
                'lat' => (float) $centre->latitude,
                'lng' => (float) $centre->longitude,
                'url' => null,
            ];
        }

        return [
            'centre' => $centre,
            'translation' => $translation,
            'name' => $name,
            'place' => $place,
            'seo' => $this->seo->forModel($centre, $locale),
            'breadcrumbs' => [
                [
                    'label' => (string) __('ui.navigation.items.centres', locale: $locale),
                    'url' => PublicPath::localized($locale, $indexPath),
                ],
                [
                    'label' => $name,
                    'url' => null,
                ],
            ],
            'markers' => $markers,
            'missing_notes' => $this->missingNotes($centre, $locale, $markers === []),
        ];
    }

    /**
     * @return list<string>
     */
    private function missingNotes(InspectionCentre $centre, string $locale, bool $mapMissing): array
    {
        $notes = [];

        if (! filled($centre->phone)) {
            $notes[] = (string) __('ui.centre.phone_missing', locale: $locale);
        }

        if (! filled($centre->email)) {
            $notes[] = (string) __('ui.centre.email_missing', locale: $locale);
        }

        if ($mapMissing) {
            $notes[] = (string) __('ui.centre.map_missing', locale: $locale);
        }

        return $notes;
    }
}
