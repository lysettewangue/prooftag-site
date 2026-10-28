<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ExpertiseCode;
use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Models\Expertise;
use App\Models\Product;
use App\Support\ContentCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublicProductShowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        Cache::flush();
    }

    public function test_draft_product_details_return_not_found(): void
    {
        foreach (ProductCode::cases() as $code) {
            $this->get('/produits/'.$code->slugFragment())->assertNotFound();
            $this->get('/en/products/'.$code->slugFragment())->assertNotFound();
        }

        $this->get('/produits/ditros-ct')->assertNotFound();
    }

    public function test_each_published_product_renders_the_shared_detail_template(): void
    {
        $now = now();

        foreach (ProductCode::cases() as $code) {
            $product = Product::query()->where('code', $code)->firstOrFail();
            $product->forceFill([
                'publication_status' => PublicationStatus::Published,
                'published_at' => $now,
                'external_url' => in_array($code, [ProductCode::CtVerif, ProductCode::Doser], true)
                    ? 'https://example.test/'.$code->slugFragment()
                    : $product->external_url,
            ])->save();
        }

        ContentCache::flush();

        foreach (ProductCode::cases() as $code) {
            $this->get('/produits/'.$code->slugFragment())
                ->assertOk()
                ->assertSee($code->publicName())
                ->assertSee('href="/produits"', false)
                ->assertSee('aria-current="page"', false)
                ->assertDontSee('DITROS-CT');
        }

        $this->get('/en/products/certidocs-ct')
            ->assertOk()
            ->assertSee('CERTIDOCS CT')
            ->assertSee('Software and equipment ecosystem', false);
    }

    public function test_external_url_cta_and_related_expertises_appear_when_present(): void
    {
        $product = Product::query()->where('code', ProductCode::CtVerif)->firstOrFail();
        $product->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => now(),
            'external_url' => 'https://ct-verif.example',
        ])->save();
        $product->translations()->where('locale', 'fr')->update([
            'external_label' => 'Vérifier une visite',
            'summary' => 'Résumé CT-VERIF publié.',
        ]);

        $expertise = Expertise::query()->where('code', ExpertiseCode::DocumentSecurity)->firstOrFail();
        $expertise->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => now(),
        ])->save();
        $product->expertises()->syncWithoutDetaching([$expertise->id]);

        ContentCache::flush();

        $this->get('/produits/ct-verif')
            ->assertOk()
            ->assertSee('Résumé CT-VERIF publié.')
            ->assertSee('Vérifier une visite')
            ->assertSee('https://ct-verif.example', false)
            ->assertSee('Expertises liées')
            ->assertSee('Sécurité documentaire')
            ->assertSee('/expertises/securite-documentaire', false)
            ->assertDontSee('ouvrir l’application métier');
    }

    public function test_missing_english_translation_hides_the_english_detail_url(): void
    {
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();
        $product->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => now(),
        ])->save();
        $product->translations()->where('locale', 'en')->delete();

        ContentCache::flush();

        $this->get('/produits/certidocs-ct')->assertOk();
        $this->get('/en/products/certidocs-ct')->assertNotFound();
    }
}
