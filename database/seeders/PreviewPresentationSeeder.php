<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Content\ContentBlocks;
use App\Enums\PageCode;
use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Models\InspectionCentre;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\TeamMember;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\HasMedia;

/**
 * Local preview only — attach visual media, city coordinates, richer About blocks.
 * Safe to re-run. Publishes the confirmed site leadership team and partner marks.
 */
class PreviewPresentationSeeder extends Seeder
{
    private const BINARIES = 'docs/legacy/archive/2026-10-20/media/binaries';

    /** @var array<string, array{0: float, 1: float}> */
    private const CITY_COORDS = [
        'douala' => [4.0511, 9.7679],
        'yaoundé' => [3.8480, 11.5021],
        'yaounde' => [3.8480, 11.5021],
        'garoua' => [9.3019, 13.3977],
        'bafoussam' => [5.4778, 10.4176],
        'bamenda' => [5.9631, 10.1591],
        'ngaoundéré' => [7.3277, 13.5847],
        'ngaoundere' => [7.3277, 13.5847],
        'maroua' => [10.5910, 14.3158],
        'bertoua' => [4.5773, 13.6846],
        'ebolowa' => [2.9000, 11.1500],
        'kribi' => [2.9373, 9.9077],
        'limbe' => [4.0186, 9.2053],
        'buea' => [4.1550, 9.2410],
    ];

    public function run(): void
    {
        $this->publishStaticContentImages();
        $this->attachProductImages();
        $this->sanitizeProductPublicCopy();
        $this->attachPostImages();
        $this->enrichAboutPages();
        $this->publishConfirmedTeamAndPartners();
        $this->fillCentreCoordinates();
        $this->ensurePublished();

        ContentCache::flush();
    }

    /**
     * Replace VER / draft notes with short public framing so local preview pages look professional.
     */
    private function sanitizeProductPublicCopy(): void
    {
        $copy = [
            ProductCode::CertidocsCtReporting->value => [
                'fr' => [
                    'summary' => 'Plateforme de suivi de l’activité des centres de contrôle (tableaux de bord et statistiques).',
                    'body' => <<<'HTML'
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
                ],
                'en' => [
                    'summary' => 'Platform for monitoring inspection-centre activity (dashboards and statistics).',
                    'body' => <<<'HTML'
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
                'fr' => [
                    'summary' => 'Plateforme publique pour vérifier la validité et l’authenticité des visites techniques au Cameroun.',
                    'body' => <<<'HTML'
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
                ],
                'en' => [
                    'summary' => 'Public platform to verify the validity and authenticity of vehicle technical inspections in Cameroon.',
                    'body' => <<<'HTML'
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
                'fr' => [
                    'summary' => 'Données de sécurité routière : collecte, recherche, analyse et statistiques d’accidents.',
                    'body' => <<<'HTML'
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
                ],
                'en' => [
                    'summary' => 'Road-safety data: collection, search, analysis, and accident statistics.',
                    'body' => <<<'HTML'
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
                'fr' => [
                    'summary' => 'Détection intelligente de véhicules et contrôles de statut réglementaire.',
                    'body' => <<<'HTML'
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
                ],
                'en' => [
                    'summary' => 'Intelligent vehicle detection and regulatory-status checks.',
                    'body' => <<<'HTML'
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

        foreach ($copy as $code => $locales) {
            $product = Product::query()->where('code', $code)->first();
            if ($product === null) {
                continue;
            }

            foreach ($locales as $locale => $fields) {
                $translation = $product->translations()->where('locale', $locale)->first();
                if ($translation === null) {
                    continue;
                }

                $translation->forceFill([
                    'summary' => $fields['summary'],
                    'body' => ['html' => $fields['body']],
                ])->save();
            }

            $updates = [
                'publication_status' => PublicationStatus::Published,
                'published_at' => $product->published_at ?? now(),
            ];

            if ($code === ProductCode::CtVerif->value) {
                $updates['external_url'] = 'https://mintctv.cm/';
            }

            $product->forceFill($updates)->save();
        }
    }

    private function publishStaticContentImages(): void
    {
        $destDir = public_path('images/content');
        File::ensureDirectoryExists($destDir);

        $map = [
            'about/archive/hero.jpg' => ['Slider-003.2-scaled.jpg', 'team.jpg', 'business-strategy-success-target-goals-scaled.jpg'],
            'about/archive/history.jpg' => ['Slider-001.png', 'Home-001.png'],
            'products/archive/network-source.jpg' => ['Slider-002.png', 'futuristic-hand-working-laptop.png'],
            'news/article-1.jpg' => ['recherche-et-developpement-scaled.jpg', 'analyse-de-donnees-et-reporting.png'],
            'news/article-2.jpg' => ['Securite-documentaire.png', '2150038844.jpg'],
            'products/certidocs.jpg' => ['Home-001.png', 'Slider-003.png'],
            'about/team.jpg' => ['team.jpg', 'team.png'],
        ];

        foreach ($map as $destName => $candidates) {
            $dest = $destDir.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $destName);
            File::ensureDirectoryExists(dirname($dest));
            if (is_file($dest) && filesize($dest) > 0) {
                continue;
            }

            foreach ($candidates as $candidate) {
                $source = $this->resolveBinary($candidate);
                if ($source === null) {
                    continue;
                }
                File::copy($source, $dest);
                break;
            }
        }
    }

    private function attachProductImages(): void
    {
        $map = [
            ProductCode::CertidocsCt->value => ['products/certidocs.jpg', 'Home-001.png', 'Slider-003.png'],
            ProductCode::CertidocsCtReporting->value => ['analyse-de-donnees-et-reporting.png'],
            ProductCode::CtVerif->value => ['CT-verif-logo-e1721923966316.png', 'CT-VERIF.png'],
            ProductCode::Doser->value => ['Image1.png', 'DOSER.png'],
            ProductCode::VideoEnforcement->value => ['futuristic-hand-working-laptop.png', 'Slider-002.png'],
        ];

        foreach ($map as $code => $files) {
            $product = Product::query()->where('code', $code)->first();
            if ($product === null) {
                continue;
            }

            foreach ($files as $file) {
                $public = public_path('images/content/'.$file);
                $source = is_file($public) ? $public : $this->resolveBinary($file);
                if ($source === null) {
                    continue;
                }

                $this->attachTo($product, $source, strtolower($code).'-preview.'.pathinfo($source, PATHINFO_EXTENSION), [
                    'fr' => 'Visuel '.$product->public_name,
                    'en' => $product->public_name.' visual',
                ]);
                break;
            }
        }
    }

    private function attachPostImages(): void
    {
        $posts = Post::query()->orderBy('id')->get();
        $files = ['news/article-1.jpg', 'news/article-2.jpg'];

        foreach ($posts->values() as $index => $post) {
            $name = $files[$index] ?? $files[0];
            $path = public_path('images/content/'.$name);
            if (! is_file($path)) {
                continue;
            }

            $this->attachTo($post, $path, 'post-'.$post->id.'-'.basename($path), [
                'fr' => 'Image illustrative de l’article',
                'en' => 'Article illustrative image',
            ]);
        }
    }

    private function enrichAboutPages(): void
    {
        $about = Page::query()->where('code', PageCode::About)->first();
        if ($about !== null) {
            foreach (['fr', 'en'] as $locale) {
                $translation = $about->translations()->where('locale', $locale)->first();
                if ($translation === null) {
                    continue;
                }

                // Hub layout carries the institutional narrative (G3-style).
                // Keep CMS blocks empty so the designed sections stay coherent.
                $translation->forceFill([
                    'blocks' => ContentBlocks::normalize([]),
                ])->save();
            }
        }

        $history = Page::query()->where('code', PageCode::History)->first();
        if ($history !== null) {
            foreach (['fr', 'en'] as $locale) {
                $translation = $history->translations()->where('locale', $locale)->first();
                if ($translation === null) {
                    continue;
                }

                $blocks = $locale === 'fr' ? [
                    [
                        'type' => 'rich_text',
                        'content' => "Le parcours de PROOFTAG-CATIS s’articule autour de la sécurisation des documents critiques du contrôle technique, puis de la modernisation progressive des outils numériques utilisés par les centres et les autorités.\n\nLes jalons nominatifs et les dates détaillées restent soumis à validation éditoriale. Cette page présente le fil conducteur, pas une chronologie inventée.",
                    ],
                    [
                        'type' => 'timeline',
                        'items' => [
                            ['year' => '01', 'text' => 'Sécurisation documentaire des vignettes et renforcement de la confiance dans les preuves du contrôle.'],
                            ['year' => '02', 'text' => 'Déploiement d’outils numériques pour accompagner la visite technique et le traitement des résultats.'],
                            ['year' => '03', 'text' => 'Orientation continue vers des données utiles à la prévention et au suivi routier.'],
                        ],
                    ],
                ] : [
                    [
                        'type' => 'rich_text',
                        'content' => "PROOFTAG-CATIS’s journey centres on securing critical technical-inspection documents, then progressively modernising the digital tools used by centres and authorities.\n\nNamed milestones and detailed dates remain under editorial validation. This page presents the through-line, not an invented chronology.",
                    ],
                    [
                        'type' => 'timeline',
                        'items' => [
                            ['year' => '01', 'text' => 'Document security for vignettes and stronger trust in inspection evidence.'],
                            ['year' => '02', 'text' => 'Digital tools to support the technical visit and result handling.'],
                            ['year' => '03', 'text' => 'Ongoing focus on data useful for prevention and road monitoring.'],
                        ],
                    ],
                ];

                $translation->forceFill([
                    'blocks' => ContentBlocks::normalize($blocks),
                ])->save();
            }
        }

        foreach ([PageCode::Organisation, PageCode::Team, PageCode::Partners, PageCode::Careers] as $code) {
            $page = Page::query()->where('code', $code)->first();
            if ($page === null) {
                continue;
            }

            foreach (['fr', 'en'] as $locale) {
                $translation = $page->translations()->where('locale', $locale)->first();
                if ($translation === null) {
                    continue;
                }

                // Designed pillars / listings carry the narrative — no stock media, no Contact CTA.
                $translation->forceFill([
                    'blocks' => ContentBlocks::normalize([]),
                ])->save();
            }
        }
    }

    private function fillCentreCoordinates(): void
    {
        foreach (InspectionCentre::query()->get() as $centre) {
            if ($centre->latitude !== null && $centre->longitude !== null) {
                continue;
            }

            $key = mb_strtolower(trim((string) $centre->city));
            $coords = self::CITY_COORDS[$key] ?? null;
            if ($coords === null) {
                // Slight jitter around Douala so markers remain visible for unknown cities.
                $coords = [4.0511 + (crc32((string) $centre->id) % 100) / 2000, 9.7679 + (crc32((string) $centre->operator_name) % 100) / 2000];
            }

            $centre->forceFill([
                'latitude' => $coords[0],
                'longitude' => $coords[1],
            ])->save();
        }
    }

    /**
     * Confirmed officers from the public site leadership listing + partner marks with logos.
     */
    private function publishConfirmedTeamAndPartners(): void
    {
        $team = [
            [
                'full_name' => 'Marceline TCHANGA',
                'sort_order' => 1,
                'slug' => 'marceline-tchanga',
                'photo' => null,
                'initials' => 'MT',
                'titles' => [
                    'fr' => 'Administrateur Général',
                    'en' => 'Chief Executive',
                ],
                'bios' => [
                    'fr' => 'Pilote l’orientation stratégique et la responsabilité institutionnelle de PROOFTAG-CATIS.',
                    'en' => 'Leads PROOFTAG-CATIS’s strategic direction and institutional accountability.',
                ],
            ],
            [
                'full_name' => 'Jocelyn NGAE',
                'sort_order' => 2,
                'slug' => 'jocelyn-ngae',
                'photo' => null,
                'initials' => 'JN',
                'titles' => [
                    'fr' => 'Administrateur Général Adjoint',
                    'en' => 'Deputy Chief Executive',
                ],
                'bios' => [
                    'fr' => 'Appuie la direction générale et la coordination des priorités opérationnelles.',
                    'en' => 'Supports executive leadership and coordination of operational priorities.',
                ],
            ],
            [
                'full_name' => 'Kelly NJIEMELI',
                'sort_order' => 3,
                'slug' => 'kelly-njiemeli',
                'photo' => null,
                'initials' => 'KN',
                'titles' => [
                    'fr' => 'Responsable Administratif et RH',
                    'en' => 'Head of Administration & HR',
                ],
                'bios' => [
                    'fr' => 'Supervise l’administration, les ressources humaines et la qualité des processus internes.',
                    'en' => 'Oversees administration, human resources, and internal process quality.',
                ],
            ],
            [
                'full_name' => 'Serge TAMPOLLA',
                'sort_order' => 4,
                'slug' => 'serge-tampolla',
                'photo' => 's-tampolla.png',
                'initials' => 'ST',
                'titles' => [
                    'fr' => 'Directeur Technique',
                    'en' => 'Technical Director',
                ],
                'bios' => [
                    'fr' => 'Dirige l’ingénierie et la fiabilité des systèmes numériques du catalogue.',
                    'en' => 'Leads engineering and reliability for the catalogue’s digital systems.',
                ],
            ],
        ];

        foreach ($team as $row) {
            $member = TeamMember::query()->updateOrCreate(
                ['full_name' => $row['full_name']],
                [
                    'sort_order' => $row['sort_order'],
                    'publication_status' => PublicationStatus::Published,
                    'published_at' => now(),
                    'scheduled_for' => null,
                ],
            );

            foreach (['fr', 'en'] as $locale) {
                $member->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $row['slug'],
                        'title' => $row['titles'][$locale],
                        'bio' => $row['bios'][$locale],
                    ],
                );
            }

            $source = null;
            if ($row['photo'] !== null) {
                $source = $this->resolveBinary($row['photo']);
            }
            if ($source === null) {
                $source = $this->makePortraitCard(
                    $row['full_name'],
                    $row['titles']['fr'],
                    $row['initials'],
                    'team-'.$row['slug'].'.png',
                );
            }

            if ($source !== null) {
                $this->attachTo($member, $source, 'team-'.$row['slug'].'.'.pathinfo($source, PATHINFO_EXTENSION), [
                    'fr' => 'Portrait de '.$row['full_name'],
                    'en' => 'Portrait of '.$row['full_name'],
                ]);
            }
        }

        // Official partners from https://www.prooftagcatis.com/partenaires/
        $partners = [
            [
                'name' => 'Ministère des Transports',
                'sort_order' => 1,
                'slug' => 'ministere-des-transports',
                'url' => null,
                'logo' => database_path('seeders/fixtures/partners/ministere-des-transports.jpg'),
                'blurbs' => [
                    'fr' => 'Autorité publique qui oriente la modernisation numérique du contrôle technique et la sécurisation des vignettes au Cameroun.',
                    'en' => 'Public authority guiding the digital modernisation of technical inspection and vignette security in Cameroon.',
                ],
            ],
            [
                'name' => 'Centres de contrôle technique',
                'sort_order' => 2,
                'slug' => 'centres-de-controle-technique',
                'url' => null,
                'logo' => database_path('seeders/fixtures/partners/centres-de-controle.jpg'),
                'blurbs' => [
                    'fr' => 'Réseau des centres de contrôle technique répartis sur le territoire national pour la réalisation des visites techniques.',
                    'en' => 'Network of technical inspection centres across the country that carry out vehicle technical inspections.',
                ],
            ],
            [
                'name' => 'Prooftag SAS',
                'sort_order' => 3,
                'slug' => 'prooftag-sas',
                'url' => 'https://www.prooftag.com/',
                'logo' => database_path('seeders/fixtures/partners/prooftag-sas.jpg'),
                'blurbs' => [
                    'fr' => 'Société française spécialisée dans la sécurisation et l’authentification documentaire. PROOFTAG-CATIS en est le représentant au Cameroun.',
                    'en' => 'French company specialised in document security and authentication. PROOFTAG-CATIS is its representative in Cameroon.',
                ],
            ],
        ];

        $keepNames = array_column($partners, 'name');
        Partner::query()
            ->whereNotIn('name', $keepNames)
            ->each(function (Partner $obsolete): void {
                $obsolete->clearMediaCollection('images');
                $obsolete->translations()->delete();
                $obsolete->delete();
            });

        foreach ($partners as $row) {
            $partner = Partner::query()->updateOrCreate(
                ['name' => $row['name']],
                [
                    'sort_order' => $row['sort_order'],
                    'url' => $row['url'],
                    'publication_status' => PublicationStatus::Published,
                    'published_at' => now(),
                    'scheduled_for' => null,
                ],
            );

            foreach (['fr', 'en'] as $locale) {
                $partner->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $row['slug'],
                        'blurb' => $row['blurbs'][$locale],
                    ],
                );
            }

            $source = is_file($row['logo']) ? $row['logo'] : null;
            if ($source !== null) {
                $this->attachTo($partner, $source, 'partner-'.$row['slug'].'.'.pathinfo($source, PATHINFO_EXTENSION), [
                    'fr' => 'Visuel '.$row['name'],
                    'en' => $row['name'].' visual',
                ]);
            }
        }
    }

    private function makePortraitCard(string $fullName, string $title, string $initials, string $fileName): ?string
    {
        if (! function_exists('imagecreatetruecolor')) {
            return null;
        }

        $path = storage_path('app/preview/'.$fileName);
        File::ensureDirectoryExists(dirname($path));

        $width = 720;
        $height = 900;
        $image = imagecreatetruecolor($width, $height);
        if ($image === false) {
            return null;
        }

        // Soft brand gradient
        for ($y = 0; $y < $height; $y++) {
            $t = $y / max(1, $height - 1);
            $r = (int) (14 + (29 - 14) * $t);
            $g = (int) (102 + (129 - 102) * $t);
            $b = (int) (151 + (185 - 151) * $t);
            $color = imagecolorallocate($image, $r, $g, $b);
            imageline($image, 0, $y, $width, $y, $color);
        }

        $white = imagecolorallocate($image, 255, 255, 255);
        $cyan = imagecolorallocate($image, 0, 174, 254);
        $navy = imagecolorallocate($image, 8, 42, 64);
        imagefilledellipse($image, (int) ($width / 2), 340, 340, 340, $cyan);
        $this->drawCenteredText($image, $initials, (int) ($width / 2), 340, 78, $white);

        // Name plate
        imagefilledrectangle($image, 40, 700, $width - 40, 860, $navy);
        $this->drawCenteredText($image, mb_strtoupper($fullName), (int) ($width / 2), 760, 22, $white);
        $this->drawCenteredText($image, $title, (int) ($width / 2), 810, 16, $cyan);

        imagepng($image, $path);
        imagedestroy($image);

        return is_file($path) ? $path : null;
    }

    private function makePartnerLogoCard(string $mark, string $label, string $fileName): ?string
    {
        if (! function_exists('imagecreatetruecolor')) {
            return null;
        }

        $path = storage_path('app/preview/'.$fileName);
        File::ensureDirectoryExists(dirname($path));

        $width = 800;
        $height = 400;
        $image = imagecreatetruecolor($width, $height);
        if ($image === false) {
            return null;
        }

        imagesavealpha($image, true);
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);

        $bg = imagecolorallocate($image, 255, 255, 255);
        $line = imagecolorallocate($image, 226, 234, 240);
        $azure = imagecolorallocate($image, 14, 102, 151);
        $cyan = imagecolorallocate($image, 0, 174, 254);
        imagefilledrectangle($image, 0, 0, $width, $height, $bg);
        imagerectangle($image, 0, 0, $width - 1, $height - 1, $line);
        imagefilledrectangle($image, 0, 0, 12, $height, $cyan);

        $this->drawCenteredText($image, $mark, (int) ($width / 2), 170, 48, $azure);
        $this->drawCenteredText($image, $label, (int) ($width / 2), 250, 18, $azure);

        imagepng($image, $path);
        imagedestroy($image);

        return is_file($path) ? $path : null;
    }

    /**
     * @param  \GdImage  $image
     */
    private function drawCenteredText(mixed $image, string $text, int $cx, int $cy, int $size, int $color): void
    {
        $font = collect([
            'C:\\Windows\\Fonts\\arialbd.ttf',
            'C:\\Windows\\Fonts\\segoeuib.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
        ])->first(fn (string $path): bool => is_file($path));

        if (is_string($font) && function_exists('imagettftext')) {
            $box = imagettfbbox($size, 0, $font, $text);
            if ($box !== false) {
                $width = $box[2] - $box[0];
                $height = $box[1] - $box[7];
                imagettftext($image, $size, 0, (int) ($cx - ($width / 2)), (int) ($cy + ($height / 2)), $color, $font, $text);

                return;
            }
        }

        $builtIn = 5;
        $textWidth = imagefontwidth($builtIn) * strlen($text);
        $textHeight = imagefontheight($builtIn);
        imagestring($image, $builtIn, (int) ($cx - ($textWidth / 2)), (int) ($cy - ($textHeight / 2)), $text, $color);
    }

    private function ensurePublished(): void
    {
        Page::query()->whereIn('code', [
            PageCode::About,
            PageCode::History,
            PageCode::Organisation,
            PageCode::Team,
            PageCode::Partners,
            PageCode::Careers,
        ])->update([
            'publication_status' => PublicationStatus::Published->value,
            'published_at' => now(),
            'scheduled_for' => null,
        ]);
    }

    /**
     * @param  array{fr: string, en: string}  $alt
     */
    private function attachTo(HasMedia&Model $owner, string $source, string $fileName, array $alt): void
    {
        $owner->clearMediaCollection('images');
        $owner
            ->addMedia($source)
            ->preservingOriginal()
            ->usingFileName($fileName)
            ->withCustomProperties([
                'alt.fr' => $alt['fr'],
                'alt.en' => $alt['en'],
                'rights' => 'PROOFTAG-CATIS — local preview from legacy archive',
            ])
            ->toMediaCollection('images');
    }

    private function resolveBinary(string $fileName): ?string
    {
        foreach ([
            base_path(self::BINARIES.'/'.$fileName),
            public_path('images/content/'.$fileName),
            database_path('seeders/fixtures/media/'.$fileName),
            database_path('seeders/fixtures/partners/'.$fileName),
        ] as $path) {
            if (is_file($path) && filesize($path) > 0) {
                return $path;
            }
        }

        return null;
    }
}
