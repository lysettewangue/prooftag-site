<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PublicationStatus;
use App\Enums\ResourceRegulatoryStatus;
use App\Models\Resource;
use App\Support\ContentCache;
use Illuminate\Database\Seeder;

/**
 * Publishes only documents retrieved from identified and verified sources.
 */
class ResourceContentMigrationSeeder extends Seeder
{
    public function run(): void
    {
        // The certified official scan of Law No. 2022/007 is 31.77 MB.
        // Raise the limit only for this controlled seed import, not for CMS uploads.
        config(['media-library.max_file_size' => 40 * 1024 * 1024]);

        $this->upsert([
            'match_slug_fr' => 'loi-2022-007-protection-patrimoine-routier',
            'regulatory_status' => ResourceRegulatoryStatus::Current,
            'authority' => 'Présidence de la République du Cameroun',
            'issued_on' => '2022-04-27',
            'relates_to_ct' => true,
            'pdf' => 'loi-2022-007-patrimoine-routier.pdf',
            'source_url' => 'https://www.prc.cm/files/4b/c2/fc/72f8f94e8b65809f9d500acff2fb1e16.pdf',
            'source_name' => 'Présidence de la République du Cameroun',
            'fr' => [
                'slug' => 'loi-2022-007-protection-patrimoine-routier',
                'title' => 'Loi n° 2022/007 du 27 avril 2022 portant protection du patrimoine routier national',
                'summary' => 'Texte officiel dont les articles 11 à 13 encadrent le contrôle technique périodique des véhicules et le certificat de visite technique.',
                'meta_title' => 'Loi n° 2022/007 sur le patrimoine routier — Ressources',
                'meta_description' => 'Texte officiel de la Loi n° 2022/007 du 27 avril 2022 portant protection du patrimoine routier national.',
            ],
            'en' => [
                'slug' => 'law-2022-007-national-roads-asset',
                'title' => 'Law No. 2022/007 of 27 April 2022 relating to the protection of the National Roads Asset',
                'summary' => 'Official text whose Sections 11 to 13 govern periodic vehicle technical inspection and the roadworthiness certificate.',
                'meta_title' => 'Law No. 2022/007 on the National Roads Asset — Resources',
                'meta_description' => 'Official text of Law No. 2022/007 of 27 April 2022 relating to the protection of the National Roads Asset.',
            ],
        ]);

        $this->upsert([
            'match_slug_fr' => 'decret-79-341-reglementation-circulation-routiere',
            'regulatory_status' => ResourceRegulatoryStatus::Historical,
            'authority' => 'Présidence de la République du Cameroun',
            'issued_on' => '1979-09-03',
            'relates_to_ct' => true,
            'pdf' => 'decret-reglementation-circulation-routiere.pdf',
            'source_url' => 'https://www.prooftagcatis.com/download/1264/',
            'source_name' => 'Archive PROOFTAG-CATIS',
            'source_label' => [
                'fr' => 'Consulter la source',
                'en' => 'View source',
            ],
            'rights' => 'Document public téléchargé depuis l’ancien site PROOFTAG-CATIS',
            'fr' => [
                'slug' => 'decret-79-341-reglementation-circulation-routiere',
                'title' => 'Décret n° 79/341 du 3 septembre 1979 portant réglementation de la circulation routière',
                'summary' => 'Texte réglementaire camerounais encadrant la circulation routière, modifié et complété par le décret n° 86/818 du 30 juin 1986.',
                'meta_title' => 'Décret n° 79/341 sur la circulation routière — Ressources',
                'meta_description' => 'Décret n° 79/341 du 3 septembre 1979 portant réglementation de la circulation routière au Cameroun.',
            ],
            'en' => [
                'slug' => 'decree-79-341-road-traffic-regulations',
                'title' => 'Decree No. 79/341 of 3 September 1979 on road traffic regulations',
                'summary' => 'Cameroonian regulatory text governing road traffic, amended and supplemented by Decree No. 86/818 of 30 June 1986.',
                'meta_title' => 'Decree No. 79/341 on road traffic — Resources',
                'meta_description' => 'Decree No. 79/341 of 3 September 1979 on road traffic regulations in Cameroon.',
            ],
        ]);

        $this->upsert([
            'match_slug_fr' => 'annuaire-statistique-transports-2025',
            'regulatory_status' => ResourceRegulatoryStatus::Current,
            'authority' => 'Ministère des Transports du Cameroun',
            'issued_on' => null,
            'relates_to_ct' => true,
            'pdf' => 'transtat-2025.pdf',
            'source_url' => 'https://www.mintransports.cm/images/news/pdfs/8368812b20a3de079c5a52ba2aea30f1-TRANSTAT-MINT-2025-FR-Ok.pdf',
            'source_name' => 'Ministère des Transports du Cameroun',
            'fr' => [
                'slug' => 'annuaire-statistique-transports-2025',
                'title' => 'Annuaire statistique des transports — Édition 2025',
                'summary' => 'Édition officielle présentant les données 2020-2024, notamment sur le transport routier, les centres de visite technique et la sécurité routière.',
                'meta_title' => 'Annuaire statistique des transports 2025 — Ressources',
                'meta_description' => 'Édition 2025 de l’Annuaire statistique des transports publiée par le Ministère des Transports du Cameroun.',
            ],
            'en' => [
                'slug' => 'transport-statistics-yearbook-2025',
                'title' => 'Transport Statistics Yearbook — 2025 Edition',
                'summary' => 'Official edition presenting 2020-2024 data, including road transport, technical inspection centres and road safety.',
                'meta_title' => 'Transport Statistics Yearbook 2025 — Resources',
                'meta_description' => '2025 Transport Statistics Yearbook published by Cameroon’s Ministry of Transport.',
            ],
        ]);

        $this->upsert([
            'match_slug_fr' => 'annuaire-statistique-transports-2023',
            'regulatory_status' => ResourceRegulatoryStatus::Historical,
            'authority' => 'Ministère des Transports du Cameroun',
            'issued_on' => null,
            'relates_to_ct' => true,
            'pdf' => 'transtat-2023.pdf',
            'source_url' => 'https://mail.mintransports.cm/images/news/pdfs/98e887cdbf916168b83ca98fad61ecd8-DOC-20240827-WA0017-250327-110622.pdf',
            'source_name' => 'Ministère des Transports du Cameroun',
            'fr' => [
                'slug' => 'annuaire-statistique-transports-2023',
                'title' => 'Annuaire statistique des transports — Édition 2023',
                'summary' => 'Édition officielle couvrant les séries 2018-2022 et comprenant des données sur les centres de visite technique et la sécurité routière.',
                'meta_title' => 'Annuaire statistique des transports 2023 — Ressources',
                'meta_description' => 'Édition 2023 de l’Annuaire statistique des transports publiée par le Ministère des Transports du Cameroun.',
            ],
            'en' => [
                'slug' => 'transport-statistics-yearbook-2023',
                'title' => 'Transport Statistics Yearbook — 2023 Edition',
                'summary' => 'Official edition covering the 2018-2022 series and including data on technical inspection centres and road safety.',
                'meta_title' => 'Transport Statistics Yearbook 2023 — Resources',
                'meta_description' => '2023 Transport Statistics Yearbook published by Cameroon’s Ministry of Transport.',
            ],
        ]);

        $this->removePlaceholderResources();
        ContentCache::flush();
    }

    /**
     * @param  array{
     *     match_slug_fr: string,
     *     regulatory_status: ResourceRegulatoryStatus,
     *     authority: string,
     *     issued_on: string|null,
     *     relates_to_ct: bool,
     *     pdf: string,
     *     source_url: string,
     *     source_name: string,
     *     source_label?: array{fr: string, en: string},
     *     rights?: string,
     *     fr: array{slug: string, title: string, summary: string, meta_title: string, meta_description: string},
     *     en: array{slug: string, title: string, summary: string, meta_title: string, meta_description: string}
     * }  $payload
     */
    private function upsert(array $payload): Resource
    {
        $resource = Resource::query()
            ->whereHas('translations', fn ($query) => $query
                ->where('locale', 'fr')
                ->where('slug', $payload['match_slug_fr']))
            ->first() ?? new Resource;

        $resource->forceFill([
            'regulatory_status' => $payload['regulatory_status'],
            'authority' => $payload['authority'],
            'issued_on' => $payload['issued_on'],
            'relates_to_ct' => $payload['relates_to_ct'],
            'publication_status' => PublicationStatus::Published,
            'published_at' => $resource->published_at ?? now(),
            'scheduled_for' => null,
        ])->save();

        foreach (['fr', 'en'] as $locale) {
            $translation = $payload[$locale];
            $resource->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'slug' => $translation['slug'],
                    'title' => $translation['title'],
                    'summary' => $translation['summary'],
                    'meta_title' => $translation['meta_title'],
                    'meta_description' => $translation['meta_description'],
                ],
            );
        }

        $this->attachVerifiedPdf(
            $resource,
            $payload['pdf'],
            $payload['source_url'],
            $payload['source_name'],
            $payload['source_label'] ?? null,
            $payload['rights'] ?? null,
        );
        $resource->products()->sync([]);

        if (! $resource->mayEnterPublished()) {
            throw new \RuntimeException("Verified resource {$payload['match_slug_fr']} has no PDF.");
        }

        return $resource->fresh();
    }

    /**
     * @param  array{fr: string, en: string}|null  $sourceLabel
     */
    private function attachVerifiedPdf(
        Resource $resource,
        string $filename,
        string $sourceUrl,
        string $sourceName,
        ?array $sourceLabel = null,
        ?string $rights = null,
    ): void
    {
        $path = database_path('seeders/fixtures/resources/'.$filename);

        if (! is_file($path) || filesize($path) < 1024) {
            throw new \RuntimeException("Missing or invalid official PDF: {$path}");
        }

        $customProperties = [
            'alt.fr' => '',
            'alt.en' => '',
            'rights' => $rights ?? 'Document public téléchargé depuis la source officielle',
            'source_url' => $sourceUrl,
            'source_name' => $sourceName,
        ];

        if ($sourceLabel !== null) {
            $customProperties['source_label'] = $sourceLabel;
        }

        $resource->clearMediaCollection('documents');
        $resource
            ->addMedia($path)
            ->preservingOriginal()
            ->usingFileName($filename)
            ->withCustomProperties($customProperties)
            ->toMediaCollection('documents');

        $resource->unsetRelation('media');
    }

    private function removePlaceholderResources(): void
    {
        $placeholderSlugs = [
            'guide-processus-controle-technique',
            'reforme-vignettes-2020',
        ];

        Resource::query()
            ->whereHas('translations', fn ($query) => $query
                ->where('locale', 'fr')
                ->whereIn('slug', $placeholderSlugs))
            ->get()
            ->each(fn (Resource $resource) => $resource->delete());
    }
}
