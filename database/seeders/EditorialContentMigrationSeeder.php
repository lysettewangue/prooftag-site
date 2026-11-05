<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ExpertiseCode;
use App\Enums\PostKind;
use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Models\Category;
use App\Models\Expertise;
use App\Models\Post;
use App\Models\Product;
use App\Support\ContentCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

/**
 * Step 158 — retain and publish selected editorial content.
 *
 * Legacy blog had zero posts (AUD-027). This migrates the 110 sample rows into
 * publishable news/article copy aligned with migrated CT / CERTIDOCS CT facts.
 */
class EditorialContentMigrationSeeder extends Seeder
{
    public function run(): void
    {
        $categories = $this->publishCategories();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->first();
        $expertiseIds = Expertise::query()
            ->whereIn('code', [
                ExpertiseCode::DocumentSecurity->value,
                ExpertiseCode::RoadSafetyPrevention->value,
                ExpertiseCode::TechnicalSupport->value,
            ])
            ->pluck('id')
            ->all();

        foreach ($this->posts() as $row) {
            $post = $this->postByFrenchSlug($row['slugs']['fr']) ?? new Post;

            $post->forceFill([
                'kind' => $row['kind'],
                'author_id' => null,
                'publication_status' => PublicationStatus::Published,
                'published_at' => $post->published_at ?? now(),
                'scheduled_for' => null,
            ])->save();

            foreach (['fr', 'en'] as $locale) {
                $post->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $row['slugs'][$locale],
                        'title' => $row['titles'][$locale],
                        'summary' => $row['summaries'][$locale],
                        'body' => ['html' => $row['bodies'][$locale]],
                        'meta_title' => $row['meta_titles'][$locale],
                        'meta_description' => $row['meta_descriptions'][$locale],
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
            $post->expertises()->sync($row['tag_expertises'] ? $expertiseIds : []);
        }

        // Keep the site-rebuild announcement out of the public site.
        $this->postByFrenchSlug('reconstruction-du-site-corporate')
            ?->forceFill([
                'publication_status' => PublicationStatus::Draft,
                'published_at' => null,
                'scheduled_for' => null,
            ])
            ->save();

        ContentCache::flush();
    }

    /**
     * @return array<int, Category>
     */
    private function publishCategories(): array
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
            $category = Category::query()->firstOrNew(['sort_order' => $sortOrder]);
            $category->forceFill([
                'publication_status' => PublicationStatus::Published,
                'published_at' => $category->published_at ?? now(),
                'scheduled_for' => null,
            ])->save();

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
     *     meta_titles: array{fr: string, en: string},
     *     meta_descriptions: array{fr: string, en: string},
     *     category_sort_orders: list<int>,
     *     tag_certidocs: bool,
     *     tag_expertises: bool
     * }>
     */
    private function posts(): array
    {
        return [
            [
                'kind' => PostKind::Article,
                'slugs' => [
                    'fr' => 'themes-controle-technique-securise',
                    'en' => 'secured-technical-inspection-themes',
                ],
                'titles' => [
                    'fr' => 'Contrôle technique sécurisé : ce que décrit CERTIDOCS CT',
                    'en' => 'Secured technical inspection: what CERTIDOCS CT describes',
                ],
                'summaries' => [
                    'fr' => 'Comprendre le processus sécurisé du contrôle technique : bancs de tests, procès-verbal, décision et vignette.',
                    'en' => 'Understand the secured technical inspection process: test benches, inspection report, decision, and vignette.',
                ],
                'meta_titles' => [
                    'fr' => 'Contrôle technique sécurisé — PROOFTAG-CATIS',
                    'en' => 'Secured technical inspection — PROOFTAG-CATIS',
                ],
                'meta_descriptions' => [
                    'fr' => 'Le contrôle technique sécurisé et le rôle de CERTIDOCS CT.',
                    'en' => 'Secured technical inspection and the role of CERTIDOCS CT.',
                ],
                'bodies' => [
                    'fr' => <<<'HTML'
<p>Le contrôle technique sécurisé au Cameroun s’appuie sur un enchaînement numérique : collecte des mesures, analyse au serveur de centre, production du procès-verbal et de la décision, puis sécurisation de la vignette lorsque le véhicule est accepté.</p>
<p><strong>CERTIDOCS CT</strong> est la solution PROOFTAG-CATIS qui décrit cet écosystème logiciel et équipements.</p>
<ul>
<li>Le serveur de centre reçoit les données des bancs de tests</li>
<li>Le procès-verbal et la décision sont générés selon le processus</li>
<li>La vignette peut intégrer un scellé à bulles et un QR code</li>
<li>Des contrôles de terrain complètent le dispositif</li>
</ul>
<p>PROOFTAG-CATIS accompagne la qualité de processus, aux côtés du Ministère des Transports.</p>
HTML,
                    'en' => <<<'HTML'
<p>Secured technical inspection in Cameroon relies on a digital sequence: collecting measurements, analysing them on the centre server, producing the inspection report and decision, then securing the vignette when the vehicle is accepted.</p>
<p><strong>CERTIDOCS CT</strong> is the PROOFTAG-CATIS solution that describes this software and equipment ecosystem.</p>
<ul>
<li>The centre server receives test-bench data</li>
<li>The inspection report and decision are generated under the process</li>
<li>The vignette may include a bubble seal and a QR code</li>
<li>Field checks complement the system</li>
</ul>
<p>PROOFTAG-CATIS supports process quality, alongside the Ministry of Transport.</p>
HTML,
                ],
                'category_sort_orders' => [1],
                'tag_certidocs' => true,
                'tag_expertises' => true,
            ],
        ];
    }
}
