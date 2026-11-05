<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ExpertiseCode;
use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Models\Expertise;
use App\Models\Product;
use App\Support\ContentCache;
use Illuminate\Database\Seeder;

/**
 * Step 153 — migrate the five expertise domains (AUD-010, AUD-015–018, AUD-026 / REC-09).
 *
 * Publishes all five with FR/EN copy aligned to site.prooftagcatis.com/expertise/.
 * Titles stay domain names (never product names).
 */
class ExpertiseContentMigrationSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->expertises() as $code => $payload) {
            $enum = ExpertiseCode::from($code);
            $expertise = Expertise::query()->where('code', $code)->firstOrFail();

            $expertise->forceFill([
                'sort_order' => $enum->sortOrder(),
                'publication_status' => PublicationStatus::Published,
                'published_at' => $expertise->published_at ?? now(),
                'scheduled_for' => null,
            ])->save();

            foreach (['fr', 'en'] as $locale) {
                $expertise->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $enum->slugs()[$locale],
                        'title' => $enum->titles()[$locale],
                        'summary' => $payload['summaries'][$locale],
                        'body' => ['html' => $payload['bodies'][$locale]],
                        'meta_title' => $payload['meta_titles'][$locale],
                        'meta_description' => $payload['meta_descriptions'][$locale],
                    ],
                );
            }
        }

        $this->syncCertidocsLinks();

        ContentCache::flush();
    }

    private function syncCertidocsLinks(): void
    {
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->first();

        if ($product === null) {
            return;
        }

        $ids = Expertise::query()
            ->whereIn('code', [
                ExpertiseCode::TechnicalSupport->value,
                ExpertiseCode::DocumentSecurity->value,
                ExpertiseCode::RoadSafetyPrevention->value,
                ExpertiseCode::DataAnalysisReporting->value,
            ])
            ->pluck('id')
            ->all();

        $product->expertises()->sync($ids);
    }

    /**
     * @return array<string, array{
     *     summaries: array{fr: string, en: string},
     *     bodies: array{fr: string, en: string},
     *     meta_titles: array{fr: string, en: string},
     *     meta_descriptions: array{fr: string, en: string}
     * }>
     */
    private function expertises(): array
    {
        return [
            ExpertiseCode::ResearchDevelopment->value => [
                'summaries' => [
                    'fr' => 'La R&D est au cœur de notre stratégie d’innovation pour des solutions adaptées aux besoins évolutifs du secteur.',
                    'en' => 'R&D is at the heart of our innovation strategy for solutions that match evolving sector needs.',
                ],
                'meta_titles' => [
                    'fr' => 'Recherche et développement — PROOFTAG-CATIS',
                    'en' => 'Research and development — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Expertise R&D de PROOFTAG-CATIS : innover pour le contrôle technique et la prévention routière.',
                    'en' => 'PROOFTAG-CATIS R&D expertise: innovating for technical inspection and road-safety prevention.',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>La recherche et le développement (R&D) sont au cœur de notre stratégie d’innovation. Nous croyons fermement que l’innovation est la clé pour rester à la pointe de notre industrie et offrir à nos clients des solutions qui répondent à leurs besoins évolutifs.</p>
<p>Les équipes travaillent sur l’architecture fonctionnelle, l’expérience d’usage et la robustesse des processus numériques liés au transport et à la sécurité routière au Cameroun.</p>
<ul>
<li>Analyse des besoins métier et traduction en solutions numériques</li>
<li>Amélioration continue des parcours et des contrôles de qualité</li>
<li>Veille technologique appliquée au contexte opérationnel local</li>
<li>Soutien transverse à l’ensemble du portefeuille PROOFTAG-CATIS</li>
</ul>
<p>Cette expertise soutient la conception et l’évolution de l’ensemble des solutions PROOFTAG-CATIS.</p>
HTML,
                    'en' => <<<'HTML'
<p>Research and development (R&D) sit at the heart of our innovation strategy. We believe innovation is key to staying ahead in our industry and delivering solutions that meet evolving client needs.</p>
<p>Teams work on functional architecture, user experience, and the robustness of digital processes linked to transport and road safety in Cameroon.</p>
<ul>
<li>Business-needs analysis translated into digital solutions</li>
<li>Continuous improvement of journeys and quality controls</li>
<li>Technology watch applied to the local operational context</li>
<li>Cross-cutting support for the whole PROOFTAG-CATIS portfolio</li>
</ul>
<p>This expertise supports the design and evolution of the full PROOFTAG-CATIS solutions portfolio.</p>
HTML,
                ],
            ],

            ExpertiseCode::TechnicalSupport->value => [
                'summaries' => [
                    'fr' => 'Assistance aux utilisateurs pour garantir la disponibilité des systèmes et la continuité des tâches opérationnelles.',
                    'en' => 'User assistance to keep systems available and operational tasks continuous.',
                ],
                'meta_titles' => [
                    'fr' => 'Support technique — PROOFTAG-CATIS',
                    'en' => 'Technical support — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Support technique PROOFTAG-CATIS : déploiement, assistance et continuité de service.',
                    'en' => 'PROOFTAG-CATIS technical support: deployment, assistance, and service continuity.',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>Les services d’assistance, ou support aux utilisateurs, consistent à garantir que les utilisateurs d’un système puissent continuer à profiter de la disponibilité de l’ensemble de ses composants pour l’accomplissement de leurs tâches.</p>
<p>Le support technique assure le lien entre les solutions déployées et les équipes qui les utilisent au quotidien dans les centres et chez les partenaires.</p>
<ul>
<li>Aide au déploiement et à la mise en service</li>
<li>Assistance aux utilisateurs et aux administrateurs locaux</li>
<li>Suivi des incidents et des demandes d’évolution prioritaires</li>
<li>Appui à la bonne configuration des équipements et logiciels</li>
</ul>
<p>Le support ne remplace pas les procédures internes des autorités ou des centres : il complète leur capacité opérationnelle.</p>
HTML,
                    'en' => <<<'HTML'
<p>User-assistance services ensure that people using a system can keep relying on the availability of all its components to complete their tasks.</p>
<p>Technical support connects deployed solutions with the teams that use them every day in centres and with partners.</p>
<ul>
<li>Deployment and go-live assistance</li>
<li>Support for users and local administrators</li>
<li>Follow-up of incidents and priority change requests</li>
<li>Help with correct configuration of equipment and software</li>
</ul>
<p>Support does not replace the internal procedures of authorities or centres: it complements their operational capacity.</p>
HTML,
                ],
            ],

            ExpertiseCode::DataAnalysisReporting->value => [
                'summaries' => [
                    'fr' => 'Exploiter les données pour éclairer les décisions, optimiser les performances et anticiper les tendances utiles.',
                    'en' => 'Using data to inform decisions, improve performance, and anticipate useful trends.',
                ],
                'meta_titles' => [
                    'fr' => 'Analyse de données et reporting — PROOFTAG-CATIS',
                    'en' => 'Data analysis and reporting — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Expertise data & reporting : indicateurs, tableaux de bord et analyse d’activité.',
                    'en' => 'Data & reporting expertise: indicators, dashboards, and activity analysis.',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>Nous comprenons l’importance cruciale des données dans la prise de décisions éclairées. Notre expertise en analyse de données et en reporting permet de tirer parti des informations disponibles pour optimiser les performances et anticiper les tendances utiles au secteur.</p>
<p>Selon les solutions concernées, cela couvre la consolidation d’indicateurs, la restitution d’activité et l’appui à des politiques fondées sur des faits mesurables.</p>
<ul>
<li>Structuration et qualité des données collectées</li>
<li>Indicateurs et tableaux de bord pour le suivi d’activité</li>
<li>Restitutions adaptées aux autorités et aux opérateurs</li>
<li>Appui à la lecture des tendances opérationnelles</li>
</ul>
<p>Les capacités publiques détaillées des plateformes de reporting restent liées à la publication produit correspondante.</p>
HTML,
                    'en' => <<<'HTML'
<p>We understand how critical data is to informed decisions. Our data-analysis and reporting expertise helps make the most of available information to improve performance and anticipate trends that matter to the sector.</p>
<p>Depending on the solutions involved, this covers consolidating indicators, presenting activity, and supporting policies based on measurable facts.</p>
<ul>
<li>Structure and quality of collected data</li>
<li>Indicators and dashboards for activity monitoring</li>
<li>Outputs tailored to authorities and operators</li>
<li>Support for reading operational trends</li>
</ul>
<p>Detailed public capabilities of reporting platforms remain tied to the matching product publication status.</p>
HTML,
                ],
            ],

            ExpertiseCode::DocumentSecurity->value => [
                'summaries' => [
                    'fr' => 'Combiner sécurisation physique et solutions numériques pour lutter contre la contrefaçon et la falsification.',
                    'en' => 'Combining physical security and digital solutions to fight counterfeiting and tampering.',
                ],
                'meta_titles' => [
                    'fr' => 'Sécurité documentaire — PROOFTAG-CATIS',
                    'en' => 'Document security — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Sécurité documentaire : unicité, authenticité, intégrité, traçabilité et contrôle des documents critiques.',
                    'en' => 'Document security: uniqueness, authenticity, integrity, traceability, and control of critical documents.',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>Nos approches de sécurité documentaire s’appuient sur la combinaison d’éléments de sécurité physique et de solutions informatiques pour lutter simultanément contre la production de faux documents et la falsification.</p>
<p>Une infrastructure de base de données sécurisée permet à l’administration de superviser les processus de délivrance de documents et d’offrir des contrôles fiables.</p>
<p>Nous proposons des solutions de protection de documents assurant :</p>
<ul>
<li>L’unicité du document</li>
<li>L’authenticité du document (anti-contrefaçon)</li>
<li>L’intégrité du contenu du document (anti-falsification)</li>
<li>La traçabilité du document tout au long de son cycle de vie</li>
<li>La possibilité de contrôle d’authenticité et du contenu</li>
<li>La possibilité d’établir des statistiques utiles à une politique de sécurité routière</li>
</ul>
<p>Ces principes s’appliquent notamment aux procès-verbaux, vignettes et éléments de sécurisation associés au contrôle technique, qu’il s’agisse de documents existants ou de nouveaux titres.</p>
HTML,
                    'en' => <<<'HTML'
<p>Our document-security approaches combine physical security elements and digital solutions to fight counterfeit production and tampering at the same time.</p>
<p>A secured database infrastructure helps administrations supervise document-issuance processes and offer reliable controls.</p>
<p>We provide document-protection solutions that support:</p>
<ul>
<li>Document uniqueness</li>
<li>Document authenticity (anti-counterfeiting)</li>
<li>Content integrity (anti-tampering)</li>
<li>Traceability throughout the document lifecycle</li>
<li>Authenticity and content verification</li>
<li>Statistics useful for road-safety policy</li>
</ul>
<p>These principles apply in particular to inspection reports (PV), vignettes (inspection certificate stickers), and related securing elements — for existing documents as well as newly issued titles.</p>
HTML,
                ],
            ],

            ExpertiseCode::RoadSafetyPrevention->value => [
                'summaries' => [
                    'fr' => 'Réduire les accidents et promouvoir des comportements responsables grâce à des processus et des données fiables.',
                    'en' => 'Reducing accidents and promoting responsible behaviour through reliable processes and data.',
                ],
                'meta_titles' => [
                    'fr' => 'Sécurité et prévention routière — PROOFTAG-CATIS',
                    'en' => 'Road safety and prevention — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Expertise prévention routière : contribuer à des routes plus sûres grâce à des contrôles et des données fiables.',
                    'en' => 'Road-safety prevention expertise: contributing to safer roads through reliable controls and data.',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>Nous sommes engagés pour la sécurité routière. Notre mission est de contribuer à réduire les accidents de la route et à promouvoir des comportements responsables grâce à des solutions numériques et des initiatives de prévention.</p>
<p>Avec des stratégies et des technologies adaptées, l’objectif est de rendre les routes plus sûres pour tous, en s’appuyant sur des contrôles et des données plus fiables.</p>
<ul>
<li>Appui aux processus qui réduisent la fraude et les documents non conformes</li>
<li>Mise à disposition d’informations utiles aux autorités et au public</li>
<li>Contribution aux politiques de prévention fondées sur des faits</li>
<li>Lien avec le contrôle technique, la vérification publique et l’analyse d’accidents lorsque les produits correspondants sont publiés</li>
</ul>
<p>Le positionnement s’inscrit dans les objectifs de réduction des décès et blessures liés à la route, sans revendiquer un rôle exclusif non validé.</p>
HTML,
                    'en' => <<<'HTML'
<p>We are committed to road safety. Our mission is to help reduce road accidents and promote responsible behaviour through digital solutions and prevention initiatives.</p>
<p>With the right strategies and technologies, the goal is safer roads for everyone, grounded in more reliable controls and data.</p>
<ul>
<li>Support for processes that reduce fraud and non-compliant documents</li>
<li>Useful information for authorities and the public</li>
<li>Contribution to prevention policies grounded in facts</li>
<li>Connection to technical inspection, public verification, and accident analysis when matching products are published</li>
</ul>
<p>The positioning aligns with goals to reduce road deaths and injuries, without claiming an unvalidated exclusive role.</p>
HTML,
                ],
            ],
        ];
    }
}
