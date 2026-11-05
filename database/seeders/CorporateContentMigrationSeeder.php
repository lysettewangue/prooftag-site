<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PageCode;
use App\Enums\PublicationStatus;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Support\ContentCache;
use Illuminate\Database\Seeder;

/**
 * Step 151 — migrate approved corporate page copy.
 *
 * Publishes About-family and contact/legal pages.
 * Does not own the Contrôle technique hub (TechnicalInspectionContentMigrationSeeder).
 * Does not create TeamMember or Partner rows.
 */
class CorporateContentMigrationSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettingsSeo();

        foreach ($this->pages() as $code => $payload) {
            $page = Page::query()->where('code', $code)->firstOrFail();

            foreach (['fr', 'en'] as $locale) {
                $page->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => PageCode::from($code)->slugs()[$locale],
                        'title' => $payload['titles'][$locale],
                        'meta_title' => $payload['meta_titles'][$locale] ?? null,
                        'meta_description' => $payload['meta_descriptions'][$locale] ?? null,
                        'blocks' => $payload['blocks'][$locale],
                    ],
                );
            }

            if ($payload['publish']) {
                $page->forceFill([
                    'publication_status' => PublicationStatus::Published,
                    'published_at' => $page->published_at ?? now(),
                    'scheduled_for' => null,
                ])->save();
            } else {
                $page->forceFill([
                    'publication_status' => PublicationStatus::Draft,
                    'published_at' => null,
                    'scheduled_for' => null,
                ])->save();
            }
        }

        ContentCache::flush();
    }

    private function seedSettingsSeo(): void
    {
        $settings = SiteSetting::query()->find(1);

        if ($settings === null) {
            return;
        }

        $settings->translations()->updateOrCreate(
            ['locale' => 'fr'],
            [
                'tagline' => $settings->translationFor('fr')?->tagline,
                'meta_title' => 'PROOFTAG-CATIS — Technologies pour le transport et la sécurité routière',
                'meta_description' => 'PROOFTAG-CATIS développe des solutions numériques pour le transport, le contrôle technique et la sécurité routière au Cameroun.',
            ],
        );

        $settings->translations()->updateOrCreate(
            ['locale' => 'en'],
            [
                'tagline' => 'Cameroon Technology Information Services',
                'meta_title' => 'PROOFTAG-CATIS — Technology for transport and road safety',
                'meta_description' => 'PROOFTAG-CATIS builds digital solutions for transport, vehicle technical inspection, and road safety in Cameroon.',
            ],
        );
    }

    /**
     * @return array<string, array{
     *     publish: bool,
     *     titles: array{fr: string, en: string},
     *     meta_titles?: array{fr: string, en: string},
     *     meta_descriptions?: array{fr: string, en: string},
     *     blocks: array{fr: list<array<string, mixed>>, en: list<array<string, mixed>>}
     * }>
     */
    private function pages(): array
    {
        return [
            PageCode::Home->value => [
                'publish' => true,
                'titles' => ['fr' => 'Accueil', 'en' => 'Home'],
                'meta_titles' => [
                    'fr' => 'Des solutions numériques au service de routes plus sûres — PROOFTAG-CATIS',
                    'en' => 'Digital solutions for safer roads — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'PROOFTAG-CATIS développe des solutions technologiques pour le transport, le contrôle technique et la sécurité routière.',
                    'en' => 'PROOFTAG-CATIS builds technology for transport, technical inspection, and road safety.',
                ],
                // Hero stays in lang/ui.php (no VER-01 exclusive-operator claim).
                'blocks' => ['fr' => [], 'en' => []],
            ],

            PageCode::About->value => [
                'publish' => true,
                'titles' => ['fr' => 'À propos', 'en' => 'About us'],
                'meta_titles' => [
                    'fr' => 'À propos de PROOFTAG-CATIS',
                    'en' => 'About PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'PROOFTAG-CATIS (CATIS) : technologies de l’information pour le transport et la sécurité routière au Cameroun.',
                    'en' => 'PROOFTAG-CATIS (CATIS): information technology for transport and road safety in Cameroon.',
                ],
                // Narrative lives in the About hub template (lang/ui.php).
                // CMS blocks remain available for verified complementary copy.
                'blocks' => [
                    'fr' => [],
                    'en' => [],
                ],
            ],

            // VER-13 / REC-01 — no named org chart; public page uses designed structure pillars.
            PageCode::Organisation->value => [
                'publish' => true,
                'titles' => ['fr' => 'Organisation', 'en' => 'Organisation'],
                'meta_titles' => [
                    'fr' => 'Organisation — PROOFTAG-CATIS',
                    'en' => 'Organisation — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Structure de PROOFTAG-CATIS : gouvernance, opérations, solutions et exigence.',
                    'en' => 'PROOFTAG-CATIS structure: governance, operations, solutions, and standards.',
                ],
                'blocks' => ['fr' => [], 'en' => []],
            ],

            PageCode::History->value => [
                'publish' => true,
                'titles' => ['fr' => 'Historique', 'en' => 'History'],
                'meta_titles' => [
                    'fr' => 'Historique — PROOFTAG-CATIS',
                    'en' => 'History — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Repères historiques de PROOFTAG-CATIS, y compris le contexte 2020 lié à la sécurisation des vignettes.',
                    'en' => 'Historical milestones for PROOFTAG-CATIS, including the 2020 vignette (inspection certificate sticker) context.',
                ],
                'blocks' => [
                    'fr' => [
                        [
                            'type' => 'rich_text',
                            'content' => 'Cette page présente des éléments de contexte historique. Elle ne remplace pas le positionnement actuel de l’entreprise, décrit sur la page À propos et sur l’accueil.',
                        ],
                        [
                            'type' => 'timeline',
                            'items' => [
                                [
                                    'year' => '2020',
                                    'text' => "Dans le contexte de la réforme des vignettes de contrôle technique, PROOFTAG-CATIS a été présenté publiquement comme opérateur chargé de la sécurisation des vignettes et fournisseur de logiciel d’enregistrement pour le processus de contrôle technique au Cameroun.\n\nCe contexte appartient à l’historique institutionnel et ne doit pas être lu comme une affirmation d’exclusivité actuelle.",
                                ],
                            ],
                        ],
                    ],
                    'en' => [
                        [
                            'type' => 'rich_text',
                            'content' => 'This page records historical context. It does not replace the company’s current positioning described on About and on the homepage.',
                        ],
                        [
                            'type' => 'timeline',
                            'items' => [
                                [
                                    'year' => '2020',
                                    'text' => "In the context of Cameroon’s technical inspection vignette (inspection certificate sticker) reform, PROOFTAG-CATIS was publicly presented as the operator responsible for securing vignettes and as a provider of registration software for the technical inspection process.\n\nThat context belongs to institutional history and should not be read as a current exclusivity claim.",
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            PageCode::Team->value => [
                'publish' => true,
                'titles' => ['fr' => 'Équipe', 'en' => 'Team'],
                'meta_titles' => [
                    'fr' => 'Équipe — PROOFTAG-CATIS',
                    'en' => 'Team — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Présentation de l’équipe PROOFTAG-CATIS. Les profils publiés apparaissent uniquement après vérification.',
                    'en' => 'Meet the PROOFTAG-CATIS team. Profiles appear only after verification.',
                ],
                // Named profiles stay empty until VER-02; page narrative lives in ui.about.pages.team.
                'blocks' => ['fr' => [], 'en' => []],
            ],

            PageCode::Partners->value => [
                'publish' => true,
                'titles' => ['fr' => 'Partenaires', 'en' => 'Partners'],
                'meta_titles' => [
                    'fr' => 'Partenaires — PROOFTAG-CATIS',
                    'en' => 'Partners — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Écosystème institutionnel et technique de PROOFTAG-CATIS. Logos nominatifs après vérification.',
                    'en' => 'PROOFTAG-CATIS institutional and technical ecosystem. Named logos after verification.',
                ],
                // Named partners stay empty until VER-09; page narrative lives in ui.about.pages.partners.
                'blocks' => ['fr' => [], 'en' => []],
            ],

            PageCode::Careers->value => [
                'publish' => true,
                'titles' => ['fr' => 'Carrières', 'en' => 'Careers'],
                'meta_titles' => [
                    'fr' => 'Carrières — PROOFTAG-CATIS',
                    'en' => 'Careers — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Offres d’emploi et de stage chez PROOFTAG-CATIS. Une liste vide est un état normal.',
                    'en' => 'Jobs and internships at PROOFTAG-CATIS. An empty list is a valid state.',
                ],
                // No Contact CTA — contact stays in the site header.
                'blocks' => ['fr' => [], 'en' => []],
            ],

            PageCode::Contact->value => [
                'publish' => true,
                'titles' => ['fr' => 'Contact', 'en' => 'Contact'],
                'meta_titles' => [
                    'fr' => 'Contact — PROOFTAG-CATIS',
                    'en' => 'Contact — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Contacter PROOFTAG-CATIS à Douala : adresse, téléphone et formulaire.',
                    'en' => 'Contact PROOFTAG-CATIS in Douala: address, phone, and enquiry form.',
                ],
                'blocks' => [
                    'fr' => [
                        [
                            'type' => 'rich_text',
                            'content' => "Écrivez-nous pour une demande d’information, un projet ou une question sur nos solutions.\n\nLes coordonnées institutionnelles affichées sur cette page sont celles du registre de contact officiel. L’adresse e-mail publique est info@prooftagcatis.com.",
                        ],
                    ],
                    'en' => [
                        [
                            'type' => 'rich_text',
                            'content' => "Write to us for information, a project enquiry, or a question about our solutions.\n\nThe institutional details on this page come from the official contact record. The public email address is info@prooftagcatis.com.",
                        ],
                    ],
                ],
            ],

            PageCode::LegalNotice->value => [
                'publish' => true,
                'titles' => ['fr' => 'Mentions légales', 'en' => 'Legal notice'],
                'meta_titles' => [
                    'fr' => 'Mentions légales — PROOFTAG-CATIS',
                    'en' => 'Legal notice — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Mentions légales du site www.prooftagcatis.com.',
                    'en' => 'Legal notice for www.prooftagcatis.com.',
                ],
                'blocks' => [
                    'fr' => [
                        [
                            'type' => 'rich_text',
                            'content' => "Éditeur du site\n\nPROOFTAG-CATIS S.A.\nImmeuble Résidence Le Carat, Hôtel de l'Air, Bonapriso\nBP 4601 Douala, Cameroun\nTél. : +237 699 19 29 19\nE-mail : info@prooftagcatis.com\nSite : https://www.prooftagcatis.com\n\nDirecteur de la publication\n\nLa direction de PROOFTAG-CATIS S.A.\n\nHébergement\n\nLe site est hébergé sur l’infrastructure retenue pour l’environnement de production (LWS). Les modalités techniques d’hébergement peuvent être précisées sur demande à info@prooftagcatis.com.\n\nPropriété intellectuelle\n\nLes contenus, marques et éléments graphiques de ce site sont protégés. Toute reproduction non autorisée est interdite.",
                        ],
                    ],
                    'en' => [
                        [
                            'type' => 'rich_text',
                            'content' => "Site publisher\n\nPROOFTAG-CATIS S.A.\nImmeuble Résidence Le Carat, Hôtel de l'Air, Bonapriso\nP.O. Box 4601 Douala, Cameroon\nTel.: +237 699 19 29 19\nEmail: info@prooftagcatis.com\nWebsite: https://www.prooftagcatis.com\n\nPublication director\n\nThe management of PROOFTAG-CATIS S.A.\n\nHosting\n\nThe site is hosted on the infrastructure selected for the production environment (LWS). Technical hosting details can be provided on request at info@prooftagcatis.com.\n\nIntellectual property\n\nContent, trademarks, and graphic elements on this site are protected. Unauthorised reproduction is prohibited.",
                        ],
                    ],
                ],
            ],

            PageCode::Privacy->value => [
                'publish' => true,
                'titles' => ['fr' => 'Politique de confidentialité', 'en' => 'Privacy policy'],
                'meta_titles' => [
                    'fr' => 'Politique de confidentialité — PROOFTAG-CATIS',
                    'en' => 'Privacy policy — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Traitement des données personnelles collectées via le site PROOFTAG-CATIS.',
                    'en' => 'How PROOFTAG-CATIS processes personal data collected on this site.',
                ],
                'blocks' => [
                    'fr' => [
                        [
                            'type' => 'rich_text',
                            'content' => "Responsable du traitement\n\nPROOFTAG-CATIS S.A., Immeuble Résidence Le Carat, Hôtel de l'Air, Bonapriso, BP 4601 Douala, Cameroun — info@prooftagcatis.com.\n\nDonnées collectées via le formulaire de contact\n\nNom, adresse e-mail, numéro de téléphone (facultatif), objet et message. Ces données servent uniquement à répondre à votre demande.\n\nBase et durée\n\nLe traitement repose sur votre demande de contact. Les messages sont conservés le temps nécessaire au suivi de la demande, puis archivés ou supprimés selon les règles internes.\n\nDestinataires\n\nLes demandes sont traitées par le personnel habilité de PROOFTAG-CATIS. Elles ne sont pas vendues à des tiers.\n\nCookies et mesure d’audience\n\nCe site n’ajoute pas de bandeau cookies tant qu’aucun traceur non essentiel n’est déployé. Si des traceurs sont ajoutés plus tard, cette page sera mise à jour.\n\nVos droits\n\nPour accéder à vos données, les corriger ou demander leur suppression, contactez info@prooftagcatis.com.",
                        ],
                    ],
                    'en' => [
                        [
                            'type' => 'rich_text',
                            'content' => "Data controller\n\nPROOFTAG-CATIS S.A., Immeuble Résidence Le Carat, Hôtel de l'Air, Bonapriso, P.O. Box 4601 Douala, Cameroon — info@prooftagcatis.com.\n\nData collected via the contact form\n\nName, email address, phone number (optional), subject, and message. These data are used only to respond to your enquiry.\n\nLegal basis and retention\n\nProcessing is based on your contact request. Messages are kept for as long as needed to handle the request, then archived or deleted under internal rules.\n\nRecipients\n\nEnquiries are handled by authorised PROOFTAG-CATIS staff. They are not sold to third parties.\n\nCookies and analytics\n\nThis site does not show a cookie banner while no non-essential trackers are deployed. If trackers are added later, this page will be updated.\n\nYour rights\n\nTo access, correct, or request deletion of your data, contact info@prooftagcatis.com.",
                        ],
                    ],
                ],
            ],

        ];
    }
}
