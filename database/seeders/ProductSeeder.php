<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ProductCode::cases() as $code) {
            $copy = $this->copy($code);

            $product = Product::query()->updateOrCreate(
                ['code' => $code->value],
                [
                    'public_name' => $code->publicName(),
                    'sort_order' => $code->sortOrder(),
                    'slug_fragment' => $code->slugFragment(),
                    'external_url' => null,
                    'publication_status' => PublicationStatus::Draft,
                ],
            );

            foreach (['fr', 'en'] as $locale) {
                $product->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $code->slugFragment(),
                        'summary' => $copy[$locale]['summary'],
                        'body' => ['html' => $copy[$locale]['body']],
                    ],
                );
            }
        }
    }

    /**
     * @return array{fr: array{summary: string, body: string}, en: array{summary: string, body: string}}
     */
    private function copy(ProductCode $code): array
    {
        return match ($code) {
            ProductCode::CertidocsCt => [
                'fr' => [
                    'summary' => 'Écosystème logiciel et équipements pour le contrôle technique sécurisé au Cameroun. Réécriture requise. CATIS n’est pas le MINT.',
                    'body' => '<p>Thèmes 015, réécriture requise : le serveur de centre reçoit les données des bancs de tests et produit un PV et une décision sécurisés lorsque le processus l’exige. La vignette (Code à Bulles, QR) fait partie du modèle de sécurité CT. CATIS accompagne la qualité de processus ; elle ne remplace pas l’autorité du MINT. Ne pas publier l’architecture interne, les identifiants, ni le nom DITROS-CT.</p>',
                ],
                'en' => [
                    'summary' => 'Software and equipment ecosystem for secured technical inspection in Cameroon. Rewrite required. CATIS is not the MINT.',
                    'body' => '<p>015 themes, rewrite required: the centre server receives test-bench data and produces a secured inspection report and decision where the process requires it. The vignette (bubble code, QR) is part of the CT security model. CATIS supports process quality; it does not replace MINT authority. Do not publish internal architecture, credentials, or the name DITROS-CT.</p>',
                ],
            ],
            ProductCode::CertidocsCtReporting => [
                'fr' => [
                    'summary' => 'Suivi centralisé de l’activité des centres (tableaux de bord, statistiques) — thème à confirmer (VER-06).',
                    'body' => '<p>Pas de liste de capacités publiée tant que VER-06 est ouvert. Brouillon local uniquement.</p>',
                ],
                'en' => [
                    'summary' => 'Centralised monitoring of inspection-centre activity (dashboards, statistics) — theme to confirm (VER-06).',
                    'body' => '<p>No public capability list while VER-06 is open. Local draft only.</p>',
                ],
            ],
            ProductCode::CtVerif => [
                'fr' => [
                    'summary' => 'Plateforme publique pour vérifier validité et authenticité des visites techniques au Cameroun. Thème à confirmer (VER-04). Lien sortant ; pas d’URL tant que VER-11 est ouvert.',
                    'body' => '<p>Mise en œuvre dans un contexte Ministère des Transports / DTR — formulation juridique à confirmer (VER-04). Ce site ne bascule pas le vérificateur. Pas d’<code>external_url</code> en V1 tant que VER-11 est ouvert.</p>',
                ],
                'en' => [
                    'summary' => 'Public platform to verify validity and authenticity of vehicle technical inspections in Cameroon. Theme to confirm (VER-04). Outbound link; no URL while VER-11 is open.',
                    'body' => '<p>Implemented in a Ministry of Transport / DTR context — legal wording to confirm (VER-04). This site does not embed the verifier. No <code>external_url</code> in V1 while VER-11 is open.</p>',
                ],
            ],
            ProductCode::Doser => [
                'fr' => [
                    'summary' => 'Données de sécurité routière : collecte, recherche, analyse, statistiques d’accidents. Thèmes 015 (VER-05). Lien sortant ; pas d’URL tant que VER-11 est ouvert.',
                    'body' => '<p>Espace partagé pour les acteurs lorsqu’un accident survient ; appui aux politiques. Confirmer la description officielle (VER-05). Pas d’<code>external_url</code> tant que VER-11 est ouvert.</p>',
                ],
                'en' => [
                    'summary' => 'Road-safety data: collection, search, analysis, accident statistics. 015 themes (VER-05). Outbound link; no URL while VER-11 is open.',
                    'body' => '<p>Shared space for actors when accidents occur; supports policy. Confirm the official description (VER-05). No <code>external_url</code> while VER-11 is open.</p>',
                ],
            ],
            ProductCode::VideoEnforcement => [
                'fr' => [
                    'summary' => 'Détection intelligente de véhicules, lecture de plaques autorisée, contrôle du statut réglementaire — thème à confirmer uniquement (VER-07).',
                    'body' => '<p>Ne pas publier d’ANPR opérationnel, de verbalisation, ni de caméras en direct sur ce site. Brouillon local. Caché tant que VER-07 est ouvert.</p>',
                ],
                'en' => [
                    'summary' => 'Intelligent vehicle detection, authorised plate reading, regulatory-status checks — theme to confirm only (VER-07).',
                    'body' => '<p>Do not publish operational ANPR, ticketing, or live cameras on this website. Local draft. Hidden while VER-07 is open.</p>',
                ],
            ],
        };
    }
}
