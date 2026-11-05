<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PageCode;
use App\Enums\PublicationStatus;
use App\Enums\SecurityPoint as SecurityPointSlot;
use App\Models\Page;
use App\Models\SecurityPoint;
use App\Support\ContentCache;
use Illuminate\Database\Seeder;

/**
 * Step 154 — migrate Contrôle technique knowledge (AUD-006 hub + AUD-001 twelve points).
 *
 * Publishes Page `technical_inspection` and all twelve SecurityPoint rows.
 * Equipment → 155. Resources → 157. No DITROS-CT naming.
 */
class TechnicalInspectionContentMigrationSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedHub();
        $this->seedSecurityPoints();
        ContentCache::flush();
    }

    private function seedHub(): void
    {
        $page = Page::query()->where('code', PageCode::TechnicalInspection->value)->firstOrFail();
        $code = PageCode::TechnicalInspection;

        foreach (['fr', 'en'] as $locale) {
            $page->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'slug' => $code->slugs()[$locale],
                    'title' => $code->titles()[$locale],
                    'meta_title' => $locale === 'fr'
                        ? 'Contrôle technique — PROOFTAG-CATIS'
                        : 'Technical inspection — PROOFTAG-CATIS',
                    'meta_description' => $locale === 'fr'
                        ? 'Processus de contrôle technique sécurisé : bancs de tests, PV, décisions, vignettes et contrôles de terrain.'
                        : 'Secured technical inspection process: test benches, inspection reports, decisions, vignettes (inspection certificate stickers), and field checks.',
                    'blocks' => $this->hubBlocks($locale),
                ],
            );
        }

        $page->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => $page->published_at ?? now(),
            'scheduled_for' => null,
        ])->save();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function hubBlocks(string $locale): array
    {
        if ($locale === 'fr') {
            return [
                [
                    'type' => 'rich_text',
                    'content' => "Le contrôle technique des véhicules au Cameroun s’appuie sur un processus numérique sécurisé de délivrance des vignettes de visites techniques.\n\nDans le centre, un serveur reçoit les résultats des bancs de tests. Lorsqu’un contrôleur termine la visite, les données sont transmises automatiquement : le serveur analyse les résultats et génère un procès-verbal accepté ou refusé selon les normes en vigueur. Un statut accepté permet d’attribuer une vignette sécurisée.\n\nLe Ministère des Transports dispose en temps réel des informations de contrôle sur le territoire via un serveur centralisé. Des agents peuvent réaliser des contrôles formels ou informels, y compris avec des smartphones.\n\nPROOFTAG-CATIS contribue au bon fonctionnement du processus et à la qualité de service, sans remplacer l’autorité du Ministère des Transports.",
                ],
            ];
        }

        return [
            [
                'type' => 'rich_text',
                'content' => "Vehicle technical inspection in Cameroon relies on a secured digital process for issuing inspection vignettes.\n\nAt the centre, a server receives test-bench results. When an inspector finishes a visit, the data is sent automatically: the server analyses the results and generates an accepted or refused inspection report under current norms. An accepted status allows a secured vignette to be issued.\n\nThe Ministry of Transport has real-time inspection information across the territory through a centralised server. Agents may perform formal or informal checks, including with smartphones.\n\nPROOFTAG-CATIS contributes to the smooth running of the process and to service quality, without replacing the authority of the Ministry of Transport.",
            ],
        ];
    }

    private function seedSecurityPoints(): void
    {
        foreach (SecurityPointSlot::cases() as $slot) {
            $bodies = $this->pointBodies($slot);
            $record = SecurityPoint::query()->firstOrNew(['sort_order' => $slot->value]);
            $record->forceFill([
                'publication_status' => PublicationStatus::Published,
                'published_at' => $record->published_at ?? now(),
                'scheduled_for' => null,
            ])->save();

            foreach (['fr', 'en'] as $locale) {
                $record->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'title' => $slot->titles()[$locale],
                        'body' => $bodies[$locale],
                    ],
                );
            }
        }
    }

    /**
     * @return array{fr: string, en: string}
     */
    private function pointBodies(SecurityPointSlot $slot): array
    {
        return match ($slot) {
            SecurityPointSlot::RegistrationCheck => [
                'fr' => 'Les informations de la carte grise sont confrontées aux données disponibles dans la base du MINT, afin de vérifier la cohérence du véhicule présenté au contrôle.',
                'en' => 'Registration-card details are checked against data available in the MINT database, to verify consistency for the vehicle presented for inspection.',
            ],
            SecurityPointSlot::MintDataUpdate => [
                'fr' => 'Les résultats et informations utiles du contrôle alimentent les bases centrales du MINT, pour maintenir à jour la vision nationale de l’activité.',
                'en' => 'Useful inspection results and information feed the MINT central databases, keeping the national view of activity up to date.',
            ],
            SecurityPointSlot::TestBenchCollection => [
                'fr' => 'Les mesures issues des bancs de tests sont collectées automatiquement via GIEGLAN, afin de réduire la saisie manuelle et les écarts de transcription.',
                'en' => 'Measurements from test benches are collected automatically via GIEGLAN, reducing manual entry and transcription gaps.',
            ],
            SecurityPointSlot::Photograph => [
                'fr' => 'Une prise de vue documente le véhicule au moment du contrôle et renforce la traçabilité du dossier.',
                'en' => 'A photograph documents the vehicle at the time of inspection and strengthens case traceability.',
            ],
            SecurityPointSlot::GpsLocation => [
                'fr' => 'La localisation GPS rattache le contrôle à un lieu, ce qui aide à détecter des pratiques anormales ou hors site.',
                'en' => 'GPS location ties the inspection to a place, helping detect abnormal or off-site practices.',
            ],
            SecurityPointSlot::VisualInspectionTablet => [
                'fr' => 'Le contrôle visuel s’appuie sur une tablette, pour guider l’inspecteur et enregistrer les constats de façon structurée.',
                'en' => 'Visual inspection uses a tablet to guide the inspector and record findings in a structured way.',
            ],
            SecurityPointSlot::ReportGeneration => [
                'fr' => 'Le procès-verbal et le rapport de test sont générés automatiquement à partir des données collectées, pour homogénéiser les livrables du centre.',
                'en' => 'The inspection report (PV) and test report are generated automatically from collected data, to standardise centre outputs.',
            ],
            SecurityPointSlot::DecisionGeneration => [
                'fr' => 'La décision d’acceptation ou de refus est produite automatiquement selon les règles du processus, à partir des résultats du contrôle.',
                'en' => 'The accept/refuse decision is produced automatically under process rules, from the inspection results.',
            ],
            SecurityPointSlot::SecuredPvPrint => [
                'fr' => 'Le résultat est imprimé sur un exemplaire de PV sécurisé, afin de limiter la falsification des documents remis.',
                'en' => 'The result is printed on a secured inspection-report copy, limiting falsification of documents issued.',
            ],
            SecurityPointSlot::VignetteSeal => [
                'fr' => 'La vignette est sécurisée avec un scellé à bulles (Code à Bulles) et un QR code, pour lier le titre physique au dossier numérique.',
                'en' => 'The vignette (inspection certificate sticker) is secured with a bubble seal (Code à Bulles) and a QR code, linking the physical title to the digital case.',
            ],
            SecurityPointSlot::MintCentralisation => [
                'fr' => 'Les données pertinentes sont centralisées sur le serveur du MINT, pour une vision nationale et un suivi par les autorités compétentes.',
                'en' => 'Relevant data is centralised on the MINT server, for a national view and follow-up by competent authorities.',
            ],
            SecurityPointSlot::FieldControl => [
                'fr' => 'Des contrôles formels ou informels peuvent être réalisés sur le terrain, y compris avec des terminaux mobiles, pour vérifier la conformité hors du centre.',
                'en' => 'Formal or informal field checks may be performed, including with mobile devices, to verify compliance outside the inspection centre.',
            ],
        };
    }
}
