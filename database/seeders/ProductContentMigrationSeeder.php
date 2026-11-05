<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Models\Product;
use App\Support\ContentCache;
use Illuminate\Database\Seeder;

/**
 * Step 152 — migrate product marketing copy from frozen baselines (015).
 *
 * Publishes CERTIDOCS CT only (verified theme rewrite, no DITROS-CT).
 * Leaves Reporting / CT-VERIF / DOSER / Video Enforcement as Draft (VER-04–07 / VER-11).
 */
class ProductContentMigrationSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->products() as $code => $payload) {
            $product = Product::query()->where('code', $code)->firstOrFail();
            $enum = ProductCode::from($code);

            $product->forceFill([
                'public_name' => $enum->publicName(),
                'sort_order' => $enum->sortOrder(),
                'slug_fragment' => $enum->slugFragment(),
                'external_url' => $payload['external_url'],
            ])->save();

            foreach (['fr', 'en'] as $locale) {
                $product->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $enum->slugFragment(),
                        'summary' => $payload['summaries'][$locale],
                        'body' => ['html' => $payload['bodies'][$locale]],
                        'external_label' => $payload['external_labels'][$locale] ?? null,
                        'meta_title' => $payload['meta_titles'][$locale] ?? null,
                        'meta_description' => $payload['meta_descriptions'][$locale] ?? null,
                    ],
                );
            }

            if ($payload['publish']) {
                $product->forceFill([
                    'publication_status' => PublicationStatus::Published,
                    'published_at' => $product->published_at ?? now(),
                    'scheduled_for' => null,
                ])->save();
            } else {
                $product->forceFill([
                    'publication_status' => PublicationStatus::Draft,
                    'published_at' => null,
                    'scheduled_for' => null,
                ])->save();
            }
        }

        ContentCache::flush();
    }

    /**
     * @return array<string, array{
     *     publish: bool,
     *     external_url: string|null,
     *     summaries: array{fr: string, en: string},
     *     bodies: array{fr: string, en: string},
     *     external_labels?: array{fr: string, en: string},
     *     meta_titles?: array{fr: string, en: string},
     *     meta_descriptions?: array{fr: string, en: string}
     * }>
     */
    private function products(): array
    {
        return [
            ProductCode::CertidocsCt->value => [
                'publish' => true,
                'external_url' => null,
                'summaries' => [
                    'fr' => 'Écosystème logiciel et équipements pour le contrôle technique sécurisé des véhicules au Cameroun.',
                    'en' => 'Software and equipment ecosystem for secured vehicle technical inspection in Cameroon.',
                ],
                'meta_titles' => [
                    'fr' => 'CERTIDOCS CT — PROOFTAG-CATIS',
                    'en' => 'CERTIDOCS CT — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'CERTIDOCS CT sécurise le processus de contrôle technique : données de bancs, PV, décisions et vignettes.',
                    'en' => 'CERTIDOCS CT secures the technical inspection process: test-bench data, inspection reports, decisions, and vignettes.',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>CERTIDOCS CT est la solution PROOFTAG-CATIS dédiée au contrôle technique sécurisé. Elle s’adresse aux centres de contrôle, aux autorités de transport et aux équipes opérationnelles de CATIS.</p>
<p>Dans le processus prévu, le serveur de centre reçoit les résultats des bancs de tests, les analyse, puis produit un procès-verbal et une décision (accepté ou refusé) lorsque le workflow l’exige. Les véhicules acceptés peuvent recevoir une vignette sécurisée intégrant des éléments tels que le Code à Bulles et un QR code.</p>
<ul>
<li>Collecte et traitement des données issues des bancs de tests</li>
<li>Génération de PV et de décisions sécurisés</li>
<li>Sécurisation de la vignette dans le modèle de contrôle technique</li>
<li>Appui à la qualité de processus pour les centres et les autorités</li>
</ul>
<p>PROOFTAG-CATIS accompagne la qualité du processus. Elle ne remplace pas l’autorité du Ministère des Transports (MINT) ni celle des services compétents.</p>
<p>Cette page présente la solution marketing. L’application opérationnelle n’est pas hébergée sur ce site.</p>
HTML,
                    'en' => <<<'HTML'
<p>CERTIDOCS CT is PROOFTAG-CATIS’s solution for secured technical inspection. It serves inspection centres, transport authorities, and CATIS operations teams.</p>
<p>In the intended process, the centre server receives test-bench results, analyses them, then produces an inspection report (PV) and a decision (accepted or refused) when the workflow requires it. Accepted vehicles can receive a secured vignette (inspection certificate sticker) that may include a bubble seal (Code à Bulles) and a QR code.</p>
<ul>
<li>Collection and processing of test-bench data</li>
<li>Generation of secured inspection reports and decisions</li>
<li>Securing of the vignette within the technical inspection model</li>
<li>Support for process quality for centres and authorities</li>
</ul>
<p>PROOFTAG-CATIS supports process quality. It does not replace the authority of the Ministry of Transport (MINT) or competent services.</p>
<p>This page is the marketing description. The operational application is not hosted on this website.</p>
HTML,
                ],
            ],

            ProductCode::CertidocsCtReporting->value => [
                'publish' => false,
                'external_url' => null,
                'summaries' => [
                    'fr' => 'Plateforme de suivi de l’activité des centres de contrôle (tableaux de bord et statistiques).',
                    'en' => 'Platform for monitoring inspection-centre activity (dashboards and statistics).',
                ],
                'meta_titles' => [
                    'fr' => 'CERTIDOCS CT Reporting Platform — PROOFTAG-CATIS',
                    'en' => 'CERTIDOCS CT Reporting Platform — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Suivi centralisé de l’activité des centres de contrôle technique.',
                    'en' => 'Centralised monitoring of technical-inspection centre activity.',
                ],
                // VER-06 still open for capability claims; keep verified framing only (no invented dashboards).
                'bodies' => [
                    'fr' => <<<'HTML'
<p>CERTIDOCS CT Reporting Platform est destinée aux promoteurs et responsables de centres qui ont besoin d’un suivi centralisé de l’activité de contrôle technique.</p>
<p>Le thème retenu pour V1 est le monitoring : tableaux de bord et statistiques d’activité des centres, en complément de CERTIDOCS CT.</p>
<ul>
<li>Vue consolidée de l’activité des centres</li>
<li>Tableaux de bord orientés suivi opérationnel</li>
<li>Statistiques d’activité pour les responsables</li>
<li>Lecture corporate du besoin, hors application métier</li>
</ul>
<p>Cette page présente le positionnement. Les écrans détaillés et les indicateurs métier restent dans l’environnement opérationnel, hors de ce site.</p>
HTML,
                    'en' => <<<'HTML'
<p>CERTIDOCS CT Reporting Platform is intended for centre owners and promoters who need centralised monitoring of technical-inspection activity.</p>
<p>The V1 theme is monitoring: dashboards and activity statistics for inspection centres, alongside CERTIDOCS CT.</p>
<ul>
<li>Consolidated view of centre activity</li>
<li>Dashboards oriented to operational follow-up</li>
<li>Activity statistics for decision-makers</li>
<li>Corporate framing of the need, outside the operational app</li>
</ul>
<p>This page presents the positioning. Detailed screens and operational indicators remain in the operational environment, not on this website.</p>
HTML,
                ],
            ],

            ProductCode::CtVerif->value => [
                'publish' => false,
                'external_url' => 'https://mintctv.cm/',
                'summaries' => [
                    'fr' => 'Plateforme publique pour vérifier la validité et l’authenticité des visites techniques au Cameroun.',
                    'en' => 'Public platform to verify the validity and authenticity of vehicle technical inspections in Cameroon.',
                ],
                'meta_titles' => [
                    'fr' => 'CT-VERIF — PROOFTAG-CATIS',
                    'en' => 'CT-VERIF — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Vérification publique des visites techniques — lien sortant après validation.',
                    'en' => 'Public verification of vehicle technical inspections — outbound link after validation.',
                ],
                'external_labels' => [
                    'fr' => 'Ouvrir CT-VERIF',
                    'en' => 'Open CT-VERIF',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>CT-VERIF permet au public de vérifier la validité et l’authenticité d’une visite technique au Cameroun.</p>
<p>La fiche s’adresse aux usagers, aux autorités et aux partenaires qui ont besoin d’une vérification simple, hors des outils internes des centres.</p>
<ul>
<li>Vérification de validité d’une visite technique</li>
<li>Contrôle d’authenticité du résultat consulté</li>
<li>Accès public via une plateforme dédiée</li>
<li>Description corporate ici ; ouverture de la plateforme via lien sortant</li>
</ul>
<p>Ce site décrit la solution et renvoie vers la plateforme. Il n’embarque pas le vérificateur et ne remplace pas les procédures officielles.</p>
HTML,
                    'en' => <<<'HTML'
<p>CT-VERIF lets the public verify the validity and authenticity of a vehicle technical inspection in Cameroon.</p>
<p>The sheet is for users, authorities, and partners who need a simple check outside centre-internal tools.</p>
<ul>
<li>Validity check for a technical inspection</li>
<li>Authenticity check of the consulted result</li>
<li>Public access through a dedicated platform</li>
<li>Corporate description here; platform opens via outbound link</li>
</ul>
<p>This site describes the solution and links out to the platform. It does not embed the verifier and does not replace official procedures.</p>
HTML,
                ],
            ],

            ProductCode::Doser->value => [
                'publish' => false,
                'external_url' => null,
                'summaries' => [
                    'fr' => 'Données de sécurité routière : collecte, recherche, analyse et statistiques d’accidents.',
                    'en' => 'Road-safety data: collection, search, analysis, and accident statistics.',
                ],
                'meta_titles' => [
                    'fr' => 'DOSER — PROOFTAG-CATIS',
                    'en' => 'DOSER — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Système de données pour la sécurité routière — lien sortant après validation.',
                    'en' => 'Road-safety data system — outbound link after validation.',
                ],
                'external_labels' => [
                    'fr' => 'Ouvrir DOSER',
                    'en' => 'Open DOSER',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>DOSER regroupe des thèmes de données utiles à la sécurité routière : collecte, recherche, analyse et statistiques liées aux accidents.</p>
<p>Il vise aussi un espace partagé pour les acteurs lorsqu’un accident survient, afin d’appuyer les politiques publiques et le suivi terrain.</p>
<ul>
<li>Collecte et organisation des données de sécurité routière</li>
<li>Recherche et consultation des informations utiles</li>
<li>Analyse et statistiques liées aux accidents</li>
<li>Appui à la coordination des acteurs concernés</li>
</ul>
<p>Cette page décrit DOSER. L’application opérationnelle n’est pas hébergée ici ; l’accès métier se fait via la plateforme dédiée lorsqu’elle est publiée.</p>
HTML,
                    'en' => <<<'HTML'
<p>DOSER covers road-safety data themes: collection, search, analysis, and accident-related statistics.</p>
<p>It also aims to provide a shared space for actors when accidents occur, supporting public policy and field follow-up.</p>
<ul>
<li>Collection and organisation of road-safety data</li>
<li>Search and consultation of useful information</li>
<li>Analysis and accident-related statistics</li>
<li>Support for coordination among relevant actors</li>
</ul>
<p>This page describes DOSER. The operational application is not hosted here; operational access is through the dedicated platform when published.</p>
HTML,
                ],
            ],

            ProductCode::VideoEnforcement->value => [
                'publish' => false,
                'external_url' => null,
                'summaries' => [
                    'fr' => 'Détection intelligente de véhicules et contrôles de statut réglementaire.',
                    'en' => 'Intelligent vehicle detection and regulatory-status checks.',
                ],
                'meta_titles' => [
                    'fr' => 'Video Enforcement — PROOFTAG-CATIS',
                    'en' => 'Video Enforcement — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Solution de détection et de contrôle réglementaire — thème à confirmer avant publication.',
                    'en' => 'Detection and regulatory-check solution — theme to confirm before publication.',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>Video Enforcement désigne la solution marketing de détection intelligente de véhicules et de contrôle du statut réglementaire, dans un cadre autorisé.</p>
<p>La fiche présente le positionnement pour les autorités et partenaires concernés par le contrôle intelligent, sans exposer d’outil opérationnel sur ce site.</p>
<ul>
<li>Détection intelligente de véhicules</li>
<li>Lecture de plaques dans un cadre autorisé</li>
<li>Contrôle du statut réglementaire</li>
<li>Positionnement corporate uniquement sur ce site</li>
</ul>
<p>Ce site ne diffuse pas d’ANPR opérationnel, de verbalisation, ni de flux caméra en direct. Aucune capacité terrain n’est exposée ici.</p>
HTML,
                    'en' => <<<'HTML'
<p>Video Enforcement is the marketing solution for intelligent vehicle detection and regulatory-status checks, within an authorised framework.</p>
<p>The sheet presents the positioning for authorities and partners concerned with intelligent control, without exposing an operational tool on this site.</p>
<ul>
<li>Intelligent vehicle detection</li>
<li>Plate reading within an authorised framework</li>
<li>Regulatory-status checks</li>
<li>Corporate positioning only on this website</li>
</ul>
<p>This site does not run operational ANPR, ticketing, or live camera feeds. No field capability is exposed here.</p>
HTML,
                ],
            ],
        ];
    }
}
