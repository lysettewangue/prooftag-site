<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PostKind;
use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class RepresentativeEditorialSeeder extends Seeder
{
    public function run(): void
    {
        $categories = $this->categories();
        $posts = $this->posts();

        $product = Product::query()->where('code', ProductCode::CertidocsCt)->first();

        foreach ($posts as $row) {
            $post = $this->postByFrenchSlug($row['slugs']['fr']);

            if ($post === null) {
                $post = Post::query()->create([
                    'kind' => $row['kind'],
                    'author_id' => null,
                    'publication_status' => PublicationStatus::Draft,
                ]);
            } else {
                $post->update([
                    'kind' => $row['kind'],
                    'publication_status' => PublicationStatus::Draft,
                ]);
            }

            foreach (['fr', 'en'] as $locale) {
                $post->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $row['slugs'][$locale],
                        'title' => $row['titles'][$locale],
                        'summary' => $row['summaries'][$locale],
                        'body' => ['html' => $row['bodies'][$locale]],
                    ],
                );
            }

            $categoryIds = [];

            foreach ($row['category_sort_orders'] as $sortOrder) {
                $categoryIds[] = $categories[$sortOrder]->id;
            }

            $post->categories()->sync($categoryIds);
            $post->products()->sync(
                $row['tag_certidocs'] && $product !== null ? [$product->id] : [],
            );
        }
    }

    /**
     * @return array<int, Category>
     */
    private function categories(): array
    {
        $rows = [
            1 => [
                'names' => ['fr' => 'Contrôle technique', 'en' => 'Technical inspection'],
                'slugs' => ['fr' => 'controle-technique', 'en' => 'technical-inspection'],
            ],
            2 => [
                'names' => ['fr' => 'Institutionnel', 'en' => 'Corporate'],
                'slugs' => ['fr' => 'institutionnel', 'en' => 'corporate'],
            ],
        ];

        $categories = [];

        foreach ($rows as $sortOrder => $row) {
            $category = Category::query()->updateOrCreate(
                ['sort_order' => $sortOrder],
                ['publication_status' => PublicationStatus::Draft],
            );

            foreach (['fr', 'en'] as $locale) {
                $category->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $row['slugs'][$locale],
                        'name' => $row['names'][$locale],
                    ],
                );
            }

            $categories[$sortOrder] = $category;
        }

        return $categories;
    }

    private function postByFrenchSlug(string $slug): ?Post
    {
        return Post::query()
            ->whereHas('translations', function (Builder $query) use ($slug): void {
                $query->where('locale', 'fr')->where('slug', $slug);
            })
            ->first();
    }

    /**
     * @return list<array{
     *     kind: PostKind,
     *     slugs: array{fr: string, en: string},
     *     titles: array{fr: string, en: string},
     *     summaries: array{fr: string, en: string},
     *     bodies: array{fr: string, en: string},
     *     category_sort_orders: list<int>,
     *     tag_certidocs: bool
     * }>
     */
    private function posts(): array
    {
        return [
            [
                'kind' => PostKind::News,
                'slugs' => [
                    'fr' => 'reconstruction-du-site-corporate',
                    'en' => 'corporate-website-rebuild',
                ],
                'titles' => [
                    'fr' => 'Reconstruction du site corporate (brouillon)',
                    'en' => 'Corporate website rebuild (draft)',
                ],
                'summaries' => [
                    'fr' => 'Exemple éditorial local. Le blog historique n’avait aucun article (AUD-027). Ne pas publier.',
                    'en' => 'Local editorial sample. The legacy blog had zero posts (AUD-027). Do not publish.',
                ],
                'bodies' => [
                    'fr' => '<p>Brouillon de travail pour le calendrier éditorial. Pas d’annonce publique. Ne pas reprendre le positionnement 2020 (VER-01) ni des faits d’équipe, de centres ou d’URL encore ouverts au registre de vérification.</p>',
                    'en' => '<p>Working draft for the editorial calendar. Not a public announcement. Do not reuse the 2020 positioning (VER-01) or unresolved team, centre, or URL facts from the verification register.</p>',
                ],
                'category_sort_orders' => [2],
                'tag_certidocs' => false,
            ],
            [
                'kind' => PostKind::Article,
                'slugs' => [
                    'fr' => 'themes-controle-technique-securise',
                    'en' => 'secured-technical-inspection-themes',
                ],
                'titles' => [
                    'fr' => 'Contrôle technique sécurisé : thèmes de travail',
                    'en' => 'Secured technical inspection: working themes',
                ],
                'summaries' => [
                    'fr' => 'Thèmes 015 pour CERTIDOCS CT. Réécriture requise. CATIS n’est pas le MINT. Le hub CT n’est pas un produit.',
                    'en' => '015 themes for CERTIDOCS CT. Rewrite required. CATIS is not the MINT. The CT hub is not a product.',
                ],
                'bodies' => [
                    'fr' => '<p>Brouillon. Écosystème logiciel et équipements pour le contrôle technique sécurisé au Cameroun. Le serveur de centre reçoit les données des bancs de tests ; la vignette (Code à Bulles, QR) fait partie du modèle de sécurité. CATIS accompagne la qualité de processus et ne remplace pas l’autorité du MINT. Ne pas publier l’architecture interne, les identifiants, ni le nom DITROS-CT. CT-VERIF et DOSER restent sans URL tant que VER-04, VER-05 et VER-11 sont ouverts.</p>',
                    'en' => '<p>Draft. Software and equipment ecosystem for secured technical inspection in Cameroon. The centre server receives test-bench data; the vignette (bubble code, QR) is part of the security model. CATIS supports process quality and does not replace MINT authority. Do not publish internal architecture, credentials, or the name DITROS-CT. CT-VERIF and DOSER stay without a URL while VER-04, VER-05 and VER-11 are open.</p>',
                ],
                'category_sort_orders' => [1],
                'tag_certidocs' => true,
            ],
        ];
    }
}
