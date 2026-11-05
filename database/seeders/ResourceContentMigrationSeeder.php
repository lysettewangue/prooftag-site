<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Enums\ResourceRegulatoryStatus;
use App\Models\Product;
use App\Models\Resource;
use App\Support\ContentCache;
use Illuminate\Database\Seeder;

/**
 * Step 157 — migrate resource library rows with current vs historical separation (VER-08 labelling).
 *
 * Attaches seed placeholder PDFs so Published is allowed. Placeholders are not official MINT texts.
 * 2020 vignette reform is labelled **historical**. A CT process guide is labelled **current**.
 */
class ResourceContentMigrationSeeder extends Seeder
{
    public function run(): void
    {
        $this->ensurePdfFixtures();

        $this->upsert([
            'match_slug_fr' => 'guide-processus-controle-technique',
            'regulatory_status' => ResourceRegulatoryStatus::Current,
            'authority' => 'PROOFTAG-CATIS',
            'issued_on' => '2026-10-28',
            'relates_to_ct' => true,
            'pdf' => 'placeholder-current.pdf',
            'link_certidocs' => true,
            'fr' => [
                'slug' => 'guide-processus-controle-technique',
                'title' => 'Guide du processus de contrôle technique',
                'summary' => 'Guide du déroulement sécurisé du contrôle technique (bancs, PV, décision, vignette). Ce n’est pas un texte réglementaire du Ministère des Transports.',
                'meta_title' => 'Guide du processus CT — Ressources — PROOFTAG-CATIS',
                'meta_description' => 'Guide courant du processus de contrôle technique sécurisé.',
            ],
            'en' => [
                'slug' => 'technical-inspection-process-guide',
                'title' => 'Technical inspection process guide',
                'summary' => 'Guide to the secured technical inspection flow (test benches, inspection report (PV), decision, vignette). Not an official Ministry of Transport regulation.',
                'meta_title' => 'CT process guide — Resources — PROOFTAG-CATIS',
                'meta_description' => 'Current guide to the secured technical-inspection process.',
            ],
        ]);

        $this->upsert([
            'match_slug_fr' => 'reforme-vignettes-2020',
            'regulatory_status' => ResourceRegulatoryStatus::Historical,
            'authority' => null,
            'issued_on' => '2020-03-03',
            'relates_to_ct' => true,
            'pdf' => 'placeholder-historical.pdf',
            'link_certidocs' => false,
            'fr' => [
                'slug' => 'reforme-vignettes-2020',
                'title' => 'Réforme des vignettes 2020 (historique)',
                'summary' => 'Document historique lié à la réforme des vignettes de 2020. Il ne constitue pas une norme actuellement en vigueur.',
                'meta_title' => 'Réforme des vignettes 2020 (historique) — PROOFTAG-CATIS',
                'meta_description' => 'Document historique sur la réforme des vignettes 2020.',
            ],
            'en' => [
                'slug' => '2020-vignette-reform',
                'title' => '2020 vignette reform (historical)',
                'summary' => 'Historical document related to the 2020 vignette (inspection certificate sticker) reform. It is not a currently applicable regulation.',
                'meta_title' => '2020 vignette reform (historical) — PROOFTAG-CATIS',
                'meta_description' => 'Historical document on the 2020 vignette (inspection certificate sticker) reform.',
            ],
        ]);

        ContentCache::flush();
    }

    /**
     * @param  array{
     *     match_slug_fr: string,
     *     regulatory_status: ResourceRegulatoryStatus,
     *     authority: string|null,
     *     issued_on: string,
     *     relates_to_ct: bool,
     *     pdf: string,
     *     link_certidocs: bool,
     *     fr: array{slug: string, title: string, summary: string, meta_title: string, meta_description: string},
     *     en: array{slug: string, title: string, summary: string, meta_title: string, meta_description: string}
     * }  $payload
     */
    private function upsert(array $payload): Resource
    {
        $resource = Resource::query()
            ->whereHas('translations', fn ($q) => $q->where('locale', 'fr')->where('slug', $payload['match_slug_fr']))
            ->first();

        if ($resource === null && $payload['match_slug_fr'] === 'reforme-vignettes-2020') {
            $resource = Resource::query()
                ->where('regulatory_status', ResourceRegulatoryStatus::Historical)
                ->where('relates_to_ct', true)
                ->orderBy('id')
                ->first();
        }

        if ($resource === null) {
            $resource = new Resource;
        }

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
            $row = $payload[$locale];
            $resource->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'slug' => $row['slug'],
                    'title' => $row['title'],
                    'summary' => $row['summary'],
                    'meta_title' => $row['meta_title'],
                    'meta_description' => $row['meta_description'],
                ],
            );
        }

        $this->attachPdf($resource, $payload['pdf']);

        if ($payload['link_certidocs']) {
            $product = Product::query()->where('code', ProductCode::CertidocsCt)->first();
            if ($product !== null) {
                $resource->products()->sync([$product->id]);
            }
        } else {
            $resource->products()->sync([]);
        }

        if (! $resource->mayEnterPublished()) {
            throw new \RuntimeException("Resource {$payload['match_slug_fr']} has no documents media; cannot publish.");
        }

        return $resource->fresh();
    }

    private function attachPdf(Resource $resource, string $filename): void
    {
        $path = database_path('seeders/fixtures/resources/'.$filename);

        if (! is_file($path)) {
            throw new \RuntimeException("Missing seed PDF fixture: {$path}");
        }

        $resource->clearMediaCollection('documents');

        $resource
            ->addMedia($path)
            ->preservingOriginal()
            ->usingFileName($filename)
            ->withCustomProperties([
                'alt.fr' => '',
                'alt.en' => '',
                'rights' => 'PROOFTAG-CATIS seed placeholder — replace with official PDF when available',
            ])
            ->toMediaCollection('documents');

        $resource->unsetRelation('media');
    }

    private function ensurePdfFixtures(): void
    {
        $dir = database_path('seeders/fixtures/resources');

        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $bytes = "%PDF-1.4\n"
            ."1 0 obj<< /Type /Catalog /Pages 2 0 R >>endobj\n"
            ."2 0 obj<< /Type /Pages /Kids [3 0 R] /Count 1 >>endobj\n"
            ."3 0 obj<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] >>endobj\n"
            ."xref\n0 4\n0000000000 65535 f \n0000000009 00000 n \n0000000068 00000 n \n0000000125 00000 n \n"
            ."trailer<< /Size 4 /Root 1 0 R >>\nstartxref\n203\n%%EOF\n";

        foreach (['placeholder-current.pdf', 'placeholder-historical.pdf'] as $name) {
            file_put_contents($dir.DIRECTORY_SEPARATOR.$name, $bytes);
        }
    }
}
