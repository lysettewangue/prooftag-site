<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Expertise;
use App\Services\Seo\SeoMeta;
use App\Services\Seo\SeoMetaResolver;
use Illuminate\Database\Eloquent\Collection;

final class ExpertiseIndexQuery
{
    public function __construct(
        private readonly ExpertiseCatalog $expertises,
        private readonly SeoMetaResolver $seo,
    ) {}

    /**
     * @return array{
     *     expertises: Collection<int, Expertise>,
     *     seo: SeoMeta
     * }
     */
    public function forLocale(string $locale): array
    {
        $resolved = $this->seo->forLocalizedIndex($locale, 'expertises', 'expertise');

        return [
            'expertises' => $this->expertises->publishedForLocale($locale),
            'seo' => new SeoMeta(
                title: (string) __('ui.expertise_index.meta_title', locale: $locale),
                description: (string) __('ui.expertise_index.meta_description', locale: $locale),
                canonical: $resolved->canonical,
                hreflang: $resolved->hreflang,
                image: $resolved->image,
                type: $resolved->type,
            ),
        ];
    }
}
