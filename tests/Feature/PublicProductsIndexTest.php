<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Models\Product;
use App\Support\ContentCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublicProductsIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        Cache::flush();
    }

    public function test_the_solutions_index_shows_an_empty_state_while_products_are_draft(): void
    {
        $this->get('/produits')
            ->assertOk()
            ->assertSee('<title>Produits &amp; Solutions — PROOFTAG-CATIS</title>', false)
            ->assertSee('data-products-index-empty', false)
            ->assertSee('Aucune solution publiée')
            ->assertDontSee('CERTIDOCS CT')
            ->assertDontSee('DITROS-CT')
            ->assertSee('aria-current="page"', false)
            ->assertSee('href="/produits"', false);

        $this->get('/en/products')
            ->assertOk()
            ->assertSee('No published solutions')
            ->assertSee('<title>Products &amp; Solutions — PROOFTAG-CATIS</title>', false)
            ->assertDontSee('Aucune solution publiée');
    }

    public function test_published_products_appear_in_sort_order_without_drafts(): void
    {
        $now = now();

        Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail()->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => $now,
        ])->save();

        Product::query()->where('code', ProductCode::Doser)->firstOrFail()->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => $now,
        ])->save();

        ContentCache::flush();

        $response = $this->get('/produits');

        $response->assertOk()
            ->assertSee('data-products-index', false)
            ->assertSee('CERTIDOCS CT')
            ->assertSee('DOSER')
            ->assertSee('/produits/certidocs-ct', false)
            ->assertSee('/produits/doser', false)
            ->assertDontSee('CERTIDOCS CT Reporting')
            ->assertDontSee('data-products-index-empty', false);

        $html = $response->getContent();
        $this->assertLessThan(
            strpos($html, 'DOSER'),
            strpos($html, 'CERTIDOCS CT'),
        );
    }

    public function test_english_index_hides_products_missing_an_english_translation(): void
    {
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();
        $product->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => now(),
        ])->save();
        $product->translations()->where('locale', 'en')->delete();

        ContentCache::flush();

        $this->get('/produits')
            ->assertOk()
            ->assertSee('CERTIDOCS CT');

        $this->get('/en/products')
            ->assertOk()
            ->assertSee('data-products-index-empty', false)
            ->assertDontSee('CERTIDOCS CT');
    }
}
