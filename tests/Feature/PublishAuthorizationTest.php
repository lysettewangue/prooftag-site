<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Products\ApproveProduct;
use App\Actions\Products\PublishProduct;
use App\Actions\Products\SubmitProductForReview;
use App\Actions\Seo\UpsertRedirect;
use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Enums\StaffRole;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_content_manager_cannot_publish_a_product(): void
    {
        $manager = User::factory()->staff(StaffRole::ContentManager)->create();
        $product = $this->approvedCertidocs();

        $this->expectException(AuthorizationException::class);
        (new PublishProduct)->handle($product, $manager);
    }

    public function test_translator_cannot_publish_a_product(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $product = $this->approvedCertidocs();

        $this->expectException(AuthorizationException::class);
        (new PublishProduct)->handle($product, $translator);
    }

    public function test_reviewer_can_publish_an_approved_product(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();
        $product = $this->approvedCertidocs();
        $published = (new PublishProduct)->handle($product, $reviewer);

        $this->assertSame('published', $published->publication_status->value);
        $this->assertTrue($published->isPubliclyVisible(now()));
    }

    public function test_support_agent_cannot_upsert_redirects(): void
    {
        $agent = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->expectException(AuthorizationException::class);
        (new UpsertRedirect)->handle($agent, [
            'from_host' => 'www.prooftagcatis.com',
            'from_path' => '/legacy-authz-test',
            'to_path' => '/',
            'status_code' => 301,
        ]);
    }

    private function approvedCertidocs(): Product
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();

        // Ensure a clean Approved state regardless of migration publish.
        $product->forceFill([
            'publication_status' => PublicationStatus::Draft,
            'published_at' => null,
        ])->save();

        (new SubmitProductForReview)->handle($product->fresh(), $admin);
        (new ApproveProduct)->handle($product->fresh(), $admin);

        return $product->fresh();
    }
}
