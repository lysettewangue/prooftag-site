<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CentreVerificationStatus;
use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Enums\RegionCode;
use App\Models\InspectionCentre;
use App\Models\Product;
use App\Models\Region;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisibilityRulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_draft_seeded_products_are_not_publicly_visible(): void
    {
        $now = new DateTimeImmutable('2026-10-21 12:00:00');

        $this->assertSame(0, Product::query()->publiclyVisible($now)->count());
        $this->assertFalse(Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail()->isPubliclyVisible($now));
    }

    public function test_published_products_appear_only_with_a_due_published_at_and_locale(): void
    {
        $now = new DateTimeImmutable('2026-10-21 12:00:00');
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();

        $product->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => $now,
        ])->save();

        $this->assertTrue($product->isPubliclyVisible($now));
        $this->assertTrue($product->isPubliclyVisibleIn('fr', $now));
        $this->assertSame(1, Product::query()->publiclyVisible($now)->forLocale('fr')->count());

        $product->forceFill([
            'published_at' => $now->modify('+1 hour'),
        ])->save();

        $this->assertFalse($product->fresh()->isPubliclyVisible($now));
        $this->assertSame(0, Product::query()->publiclyVisible($now)->count());
    }

    public function test_scheduled_products_stay_hidden_until_the_status_becomes_published(): void
    {
        $now = new DateTimeImmutable('2026-10-21 12:00:00');
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();

        $product->forceFill([
            'publication_status' => PublicationStatus::Scheduled,
            'scheduled_for' => $now->modify('-1 hour'),
            'published_at' => null,
        ])->save();

        $this->assertFalse($product->fresh()->isPubliclyVisible($now));
        $this->assertSame(0, Product::query()->publiclyVisible($now)->count());
    }

    public function test_missing_english_translation_hides_the_english_url(): void
    {
        $now = new DateTimeImmutable('2026-10-21 12:00:00');
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();

        $product->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => $now,
        ])->save();

        $product->translations()->where('locale', 'en')->delete();

        $this->assertTrue($product->fresh()->isPubliclyVisible($now));
        $this->assertFalse($product->fresh()->isPubliclyVisibleIn('en', $now));
        $this->assertSame(0, Product::query()->publiclyVisible($now)->forLocale('en')->count());
        $this->assertSame(1, Product::query()->publiclyVisible($now)->forLocale('fr')->count());
    }

    public function test_ct_verif_cannot_enter_published_without_an_external_url(): void
    {
        $product = Product::query()->where('code', ProductCode::CtVerif)->firstOrFail();

        $this->assertFalse($product->mayEnterPublished());

        $product->forceFill(['external_url' => 'https://ct-verif.example'])->save();

        $this->assertTrue($product->fresh()->mayEnterPublished());
    }

    public function test_unverified_centres_are_excluded_from_the_public_directory(): void
    {
        $listedBefore = InspectionCentre::query()->publiclyListed()->count();
        $region = Region::query()->where('code', RegionCode::Littoral)->firstOrFail();

        $unverified = InspectionCentre::query()->create([
            'region_id' => $region->id,
            'operator_name' => 'Draft Centre',
            'city' => 'Douala',
        ]);
        $unverified->translations()->create([
            'locale' => 'fr',
            'slug' => 'draft-centre-douala',
            'name' => 'Draft Centre Douala',
        ]);

        $active = InspectionCentre::query()->create([
            'region_id' => $region->id,
            'operator_name' => 'Active Centre',
            'city' => 'Douala',
            'verification_status' => CentreVerificationStatus::Active,
        ]);
        $active->translations()->create([
            'locale' => 'fr',
            'slug' => 'active-centre-douala',
            'name' => 'Active Centre Douala',
        ]);

        $this->assertFalse($unverified->isPubliclyListed());
        $this->assertTrue($active->isPubliclyListed());
        $this->assertTrue($active->isPubliclyVisibleIn('fr'));
        $this->assertFalse($active->isPubliclyVisibleIn('en'));
        $this->assertSame($listedBefore + 1, InspectionCentre::query()->publiclyListed()->count());
    }
}
