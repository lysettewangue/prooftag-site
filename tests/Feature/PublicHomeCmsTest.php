<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ExpertiseCode;
use App\Enums\PageCode;
use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Models\Expertise;
use App\Models\Page;
use App\Models\Product;
use App\Queries\ExpertiseCatalog;
use App\Queries\HomePageQuery;
use App\Support\ContentCache;
use App\Support\PublicPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublicHomeCmsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        Cache::flush();
    }

    public function test_the_homepage_shows_empty_cms_states_while_seeds_are_draft(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('data-home-products-empty', false)
            ->assertSee('Aucune solution n’est encore publiée.')
            ->assertSee('data-home-expertises-empty', false)
            ->assertSee('Aucune expertise n’est encore publiée.')
            ->assertDontSee('DITROS-CT')
            ->assertDontSee('opérateur exclusif');

        $this->get('/en')
            ->assertOk()
            ->assertSee('No solutions are published yet.')
            ->assertSee('No expertise areas are published yet.');
    }

    public function test_published_products_and_expertises_appear_on_the_homepage(): void
    {
        $now = now();

        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();
        $product->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => $now,
        ])->save();

        $expertise = Expertise::query()->where('code', ExpertiseCode::DocumentSecurity)->firstOrFail();
        $expertise->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => $now,
        ])->save();

        ContentCache::flush();

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('data-home-products', false)
            ->assertSee('CERTIDOCS CT')
            ->assertSee('/produits/certidocs-ct', false)
            ->assertDontSee('data-home-products-empty', false)
            ->assertSee('data-home-expertises', false)
            ->assertSee('Sécurité documentaire')
            ->assertSee('/expertises/securite-documentaire', false)
            ->assertDontSee('data-home-expertises-empty', false);
    }

    public function test_homepage_seo_uses_published_page_meta_when_present(): void
    {
        $page = Page::query()->where('code', PageCode::Home)->firstOrFail();
        $page->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => now(),
        ])->save();
        $page->translations()->where('locale', 'fr')->update([
            'meta_title' => 'Titre SEO accueil',
            'meta_description' => 'Description SEO accueil vérifiée.',
        ]);

        ContentCache::flush();

        $this->get('/')
            ->assertOk()
            ->assertSee('<title>Titre SEO accueil</title>', false)
            ->assertSee('content="Description SEO accueil vérifiée."', false)
            ->assertSee('rel="canonical"', false)
            ->assertSee('hreflang="fr"', false)
            ->assertSee('hreflang="en"', false);
    }

    public function test_home_page_query_limits_products_to_five(): void
    {
        $now = now();

        Product::query()->update([
            'publication_status' => PublicationStatus::Published,
            'published_at' => $now,
        ]);

        ContentCache::flush();

        $payload = app(HomePageQuery::class)->forLocale('fr');

        $this->assertCount(5, $payload['products']);
        $this->assertSame(
            ProductCode::cases()[0]->publicName(),
            $payload['products']->first()->public_name,
        );
    }

    public function test_expertise_catalog_and_public_paths_are_locale_aware(): void
    {
        $now = now();
        $expertise = Expertise::query()->where('code', ExpertiseCode::ResearchDevelopment)->firstOrFail();
        $expertise->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => $now,
        ])->save();

        ContentCache::flush();

        $this->assertSame(1, (new ExpertiseCatalog)->publishedForLocale('fr')->count());
        $this->assertSame('/expertises/recherche-et-developpement', PublicPath::for($expertise->fresh(), 'fr'));
        $this->assertSame('/en/expertise/research-and-development', PublicPath::for($expertise->fresh(), 'en'));
    }

    public function test_draft_products_never_render_as_ghost_cards(): void
    {
        $this->assertSame(0, (new ExpertiseCatalog)->publishedForLocale('fr')->count());

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('data-home-products"', $html);
        $this->assertSame(0, substr_count($html, 'CERTIDOCS CT Reporting'));
    }
}
