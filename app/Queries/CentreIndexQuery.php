<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\InspectionCentre;
use App\Models\Region;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\PublicPath;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class CentreIndexQuery
{
    public function __construct(
        private readonly CentreDirectory $directory,
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     centres: LengthAwarePaginator<int, InspectionCentre>,
     *     regions: Collection<int, Region>,
     *     search: string,
     *     region_id: int|null,
     *     seo: SeoMeta,
     *     markers: list<array{name: string, city: string, lat: float, lng: float, url: string|null}>,
     *     index_url: string
     * }
     */
    public function forLocale(string $locale, ?string $search = null, ?int $regionId = null): array
    {
        $search = is_string($search) ? trim($search) : '';
        $search = $search === '' ? null : $search;

        if ($regionId !== null && Region::query()->whereKey($regionId)->doesntExist()) {
            $regionId = null;
        }

        $path = $locale === Locale::English->value ? 'inspection-centres' : 'centres-de-controle';
        $resolved = $this->seo->forLocalizedIndex($locale, 'centres-de-controle', 'inspection-centres');
        $centres = $this->directory->paginatePublic($locale, $regionId, $search)->withQueryString();

        return [
            'centres' => $centres,
            'regions' => Region::query()
                ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
                ->orderBy('code')
                ->get(),
            'search' => $search ?? '',
            'region_id' => $regionId,
            'index_url' => PublicPath::localized($locale, $path),
            'seo' => new SeoMeta(
                title: (string) __('ui.centres.meta_title', locale: $locale),
                description: (string) __('ui.centres.meta_description', locale: $locale),
                canonical: $resolved->canonical,
                hreflang: $resolved->hreflang,
                image: $resolved->image,
                type: $resolved->type,
            ),
            'markers' => $this->markers($centres->getCollection(), $locale),
        ];
    }

    /**
     * @param  Collection<int, InspectionCentre>  $centres
     * @return list<array{name: string, city: string, lat: float, lng: float, url: string|null}>
     */
    private function markers(Collection $centres, string $locale): array
    {
        $markers = [];

        foreach ($centres as $centre) {
            if ($centre->latitude === null || $centre->longitude === null) {
                continue;
            }

            $translation = $centre->translationFor($locale);

            $markers[] = [
                'name' => (string) ($translation?->name ?: $centre->operator_name),
                'city' => (string) $centre->city,
                'lat' => (float) $centre->latitude,
                'lng' => (float) $centre->longitude,
                'url' => PublicPath::for($centre, $locale),
            ];
        }

        return $markers;
    }
}
