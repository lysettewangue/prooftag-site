<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\EquipmentLifecycle;
use App\Enums\PublicationStatus;
use App\Models\Equipment;
use App\Support\ContentCache;
use Illuminate\Database\Seeder;

/**
 * Step 155 — migrate equipment catalogue copy (AUD-006 roles).
 *
 * Publishes four current process equipment items for the public hub.
 * Not a product catalogue. No DITROS-CT. No invented fleet SKUs.
 */
class EquipmentContentMigrationSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->items() as $item) {
            $equipment = Equipment::query()->firstOrNew(['sort_order' => $item['sort_order']]);
            $equipment->forceFill([
                'lifecycle' => $item['lifecycle'],
                'publication_status' => PublicationStatus::Published,
                'published_at' => $equipment->published_at ?? now(),
                'scheduled_for' => null,
            ])->save();

            foreach (['fr', 'en'] as $locale) {
                $equipment->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $item['slugs'][$locale],
                        'title' => $item['titles'][$locale],
                        'body' => ['html' => $item['bodies'][$locale]],
                        'meta_title' => $item['meta_titles'][$locale],
                        'meta_description' => $item['meta_descriptions'][$locale],
                    ],
                );
            }
        }

        // Keep internal migration leftovers off the public catalogue.
        Equipment::query()
            ->whereHas('translations', fn ($q) => $q->whereIn('slug', [
                'workflow-legacy-2020',
                'legacy-2020-workflow',
            ]))
            ->update([
                'publication_status' => PublicationStatus::Draft,
                'published_at' => null,
                'scheduled_for' => null,
            ]);

        ContentCache::flush();
    }

    /**
     * @return list<array{
     *     sort_order: int,
     *     lifecycle: EquipmentLifecycle,
     *     slugs: array{fr: string, en: string},
     *     titles: array{fr: string, en: string},
     *     bodies: array{fr: string, en: string},
     *     meta_titles: array{fr: string, en: string},
     *     meta_descriptions: array{fr: string, en: string}
     * }>
     */
    private function items(): array
    {
        return [
            [
                'sort_order' => 1,
                'lifecycle' => EquipmentLifecycle::Current,
                'slugs' => ['fr' => 'banc-de-tests', 'en' => 'test-bench'],
                'titles' => ['fr' => 'Banc de tests', 'en' => 'Test bench'],
                'meta_titles' => [
                    'fr' => 'Banc de tests — Contrôle technique — PROOFTAG-CATIS',
                    'en' => 'Test bench — Technical inspection — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Rôle du banc de tests dans le processus de contrôle technique sécurisé.',
                    'en' => 'Role of the test bench in the secured technical-inspection process.',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>Le banc de tests fournit les mesures techniques du véhicule pendant le contrôle. Dans le workflow sécurisé, ces données sont collectées automatiquement (notamment via GIEGLAN) pour alimenter le serveur de centre.</p>
<ul>
<li><strong>Mesures issues des essais techniques</strong></li>
<li><strong>Collecte automatique vers le système de centre</strong></li>
<li><strong>Contribution à la génération du PV et de la décision</strong></li>
</ul>
HTML,
                    'en' => <<<'HTML'
<p>The test bench provides the vehicle’s technical measurements during inspection. In the secured workflow, those data are collected automatically (including via GIEGLAN) to feed the centre server.</p>
<ul>
<li><strong>Measurements from technical tests</strong></li>
<li><strong>Automatic collection into the centre system</strong></li>
<li><strong>Contribution to inspection report (PV) and decision generation</strong></li>
</ul>
HTML,
                ],
            ],
            [
                'sort_order' => 2,
                'lifecycle' => EquipmentLifecycle::Current,
                'slugs' => ['fr' => 'tablette-controle-visuel', 'en' => 'visual-inspection-tablet'],
                'titles' => ['fr' => 'Tablette de contrôle visuel', 'en' => 'Visual inspection tablet'],
                'meta_titles' => [
                    'fr' => 'Tablette de contrôle visuel — PROOFTAG-CATIS',
                    'en' => 'Visual inspection tablet — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Tablette utilisée pour guider et enregistrer le contrôle visuel au centre.',
                    'en' => 'Tablet used to guide and record visual inspection at the centre.',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>La tablette de contrôle visuel accompagne l’inspecteur pendant l’examen du véhicule. Elle structure les constats et les rattache au dossier numérique du contrôle.</p>
<p>Dans le processus décrit sur le hub Contrôle technique, elle complète la collecte des bancs et la génération automatique des documents.</p>
<ul>
<li><strong>Guidage du contrôle visuel</strong></li>
<li><strong>Saisie structurée des observations</strong></li>
<li><strong>Lien avec le dossier et les livrables du centre</strong></li>
</ul>
HTML,
                    'en' => <<<'HTML'
<p>The visual-inspection tablet supports the inspector during the vehicle examination. It structures findings and attaches them to the digital inspection case.</p>
<p>In the process described on the Technical inspection hub, it complements bench collection and automatic document generation.</p>
<ul>
<li><strong>Guidance for visual inspection</strong></li>
<li><strong>Structured capture of observations</strong></li>
<li><strong>Link to the case and centre outputs</strong></li>
</ul>
HTML,
                ],
            ],
            [
                'sort_order' => 3,
                'lifecycle' => EquipmentLifecycle::Current,
                'slugs' => ['fr' => 'serveur-de-centre', 'en' => 'centre-server'],
                'titles' => ['fr' => 'Serveur de centre', 'en' => 'Centre server'],
                'meta_titles' => [
                    'fr' => 'Serveur de centre — Contrôle technique — PROOFTAG-CATIS',
                    'en' => 'Centre server — Technical inspection — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Le serveur de centre analyse les données, produit PV/décision et prépare la vignette sécurisée.',
                    'en' => 'The centre server analyses data, produces the inspection report (PV) and decision, and prepares the secured vignette.',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>Le serveur de centre est le nœud local du processus : il reçoit les résultats des bancs de tests, les analyse, puis produit le procès-verbal et la décision (accepté ou refusé) lorsque le workflow l’exige.</p>
<p>Pour les véhicules acceptés, il contribue à la préparation de la vignette sécurisée. Les données pertinentes peuvent ensuite être centralisées pour les autorités compétentes.</p>
<ul>
<li><strong>Réception et analyse des données de contrôle</strong></li>
<li><strong>Génération du PV et de la décision</strong></li>
<li><strong>Préparation de la vignette sécurisée</strong></li>
</ul>
HTML,
                    'en' => <<<'HTML'
<p>The centre server is the local process node: it receives test-bench results, analyses them, then produces the inspection report (PV) and decision (accepted or refused) when the workflow requires it.</p>
<p>For accepted vehicles, it contributes to preparing the secured vignette (inspection certificate sticker). Relevant data can then be centralised for competent authorities.</p>
<ul>
<li><strong>Reception and analysis of inspection data</strong></li>
<li><strong>Generation of the inspection report (PV) and decision</strong></li>
<li><strong>Preparation of the secured vignette</strong></li>
</ul>
HTML,
                ],
            ],
            [
                'sort_order' => 4,
                'lifecycle' => EquipmentLifecycle::Current,
                'slugs' => ['fr' => 'douchette', 'en' => 'barcode-scanner'],
                'titles' => ['fr' => 'Douchette', 'en' => 'Barcode scanner'],
                'meta_titles' => [
                    'fr' => 'Douchette — Contrôle technique — PROOFTAG-CATIS',
                    'en' => 'Barcode scanner — Technical inspection — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Rôle de la douchette (lecteur code-barres / QR) dans le processus de contrôle technique.',
                    'en' => 'Role of the barcode / QR scanner in the technical-inspection process.',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>La douchette est fournie aux centres pour lire rapidement les codes portés par les documents et la vignette (notamment le QR code associé au scellé à bulles), et rattacher la lecture au dossier numérique du contrôle.</p>
<ul>
<li><strong>Lecture des codes de la vignette et des documents</strong></li>
<li><strong>Accélération de la saisie et réduction des erreurs de transcription</strong></li>
<li><strong>Lien avec le dossier numérique du centre</strong></li>
</ul>
HTML,
                    'en' => <<<'HTML'
<p>The barcode scanner is supplied to centres to quickly read codes on documents and the vignette (including the QR code linked to the bubble seal), and to attach that reading to the digital inspection case.</p>
<ul>
<li><strong>Reading vignette and document codes</strong></li>
<li><strong>Faster capture and fewer transcription errors</strong></li>
<li><strong>Link to the centre’s digital case</strong></li>
</ul>
HTML,
                ],
            ],
        ];
    }
}
