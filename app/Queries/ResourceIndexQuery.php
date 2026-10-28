<?php

declare(strict_types=1);

namespace App\Queries;

use App\Enums\Locale;
use App\Enums\ResourceRegulatoryStatus;
use App\Models\Resource;
use App\Services\Seo\SeoMeta;
use App\Support\PublicPath;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ResourceIndexQuery
{
    public function __construct(
        private readonly ResourceLibrary $library,
    ) {}

    /**
     * @return array{
     *     resources: LengthAwarePaginator<int, Resource>,
     *     status: ResourceRegulatoryStatus|null,
     *     statuses: list<ResourceRegulatoryStatus>,
     *     seo: SeoMeta,
     *     index_url: string
     * }
     */
    public function forLocale(string $locale, ?string $status = null): array
    {
        $filter = is_string($status) && $status !== ''
            ? ResourceRegulatoryStatus::tryFrom($status)
            : null;

        $path = $locale === Locale::English->value ? 'resources' : 'ressources';
        $indexUrl = PublicPath::localized($locale, $path);

        return [
            'resources' => $this->library->paginatePublic($locale, $filter)->withQueryString(),
            'status' => $filter,
            'statuses' => ResourceRegulatoryStatus::cases(),
            'index_url' => $indexUrl,
            'seo' => new SeoMeta(
                title: (string) __('ui.resources.meta_title', locale: $locale),
                description: (string) __('ui.resources.meta_description', locale: $locale),
                canonical: PublicPath::canonical($indexUrl),
                hreflang: [
                    Locale::French->value => PublicPath::canonical(PublicPath::localized(Locale::French->value, 'ressources')),
                    Locale::English->value => PublicPath::canonical(PublicPath::localized(Locale::English->value, 'resources')),
                ],
            ),
        ];
    }
}
