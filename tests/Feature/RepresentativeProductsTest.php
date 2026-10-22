<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepresentativeProductsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_five_solutions_have_draft_baseline_copy_and_no_outbound_urls(): void
    {
        $this->assertSame(5, Product::query()->count());
        $this->assertTrue(Product::query()->where('publication_status', '!=', PublicationStatus::Draft)->doesntExist());

        foreach (Product::query()->with('translations')->get() as $product) {
            $this->assertNotNull($product->code);
            $this->assertNull($product->external_url, $product->code->value);

            foreach (['fr', 'en'] as $locale) {
                $translation = $product->translationFor($locale);
                $this->assertNotNull($translation, $product->code->value.' '.$locale);
                $this->assertNotSame('', trim((string) $translation->summary));
                $this->assertIsArray($translation->body);
                $this->assertArrayHasKey('html', $translation->body);
            }
        }

        $this->assertSame(
            ProductCode::CtVerif->slugFragment(),
            Product::query()->where('code', ProductCode::CtVerif)->firstOrFail()->slug_fragment,
        );
        $this->assertSame(
            ProductCode::Doser->slugFragment(),
            Product::query()->where('code', ProductCode::Doser)->firstOrFail()->slug_fragment,
        );
    }
}
