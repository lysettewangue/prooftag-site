<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Models\SecurityPoint;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use App\Support\PublicPath;
use Illuminate\Database\Eloquent\Collection;

final class SecurityPointsIndexQuery
{
    public function __construct(
        private readonly SecurityPointCatalog $securityPoints,
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     security_points: Collection<int, SecurityPoint>,
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
            'controle-technique/points-de-securisation',
            'technical-inspection/security-points',
        );
        $hubUrl = PublicPath::localized($locale, $hubPath);

        return [
            'security_points' => $this->securityPoints->publishedForLocale($locale),
            'hub_url' => $hubUrl,
            'seo' => new SeoMeta(
                title: (string) __('ui.security_points.meta_title', locale: $locale),
                description: (string) __('ui.security_points.meta_description', locale: $locale),
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
                    'label' => (string) __('ui.security_points.title', locale: $locale),
                    'url' => null,
                ],
            ],
        ];
    }
}
