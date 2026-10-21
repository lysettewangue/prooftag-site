<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProductCode;
use App\Enums\StaffRole;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_can_list_the_five_products(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(ProductResource::getUrl('index'))
            ->assertOk()
            ->assertSee('CERTIDOCS CT')
            ->assertSee('CT-VERIF')
            ->assertSee('DOSER');

        $this->assertSame(5, Product::query()->count());
        $this->assertFalse(ProductResource::canCreate());
    }

    public function test_technical_editor_can_open_a_product(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();

        $this->actingAs($editor)
            ->get(ProductResource::getUrl('edit', ['record' => $product]))
            ->assertOk();
    }

    public function test_translator_can_open_a_product_to_translate(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $product = Product::query()->firstOrFail();

        $this->actingAs($translator)
            ->get(ProductResource::getUrl('edit', ['record' => $product]))
            ->assertOk();
    }

    public function test_reviewer_can_open_products_to_publish(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(ProductResource::getUrl('index'))
            ->assertOk();
    }

    public function test_communications_editor_cannot_open_products(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(ProductResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_support_cannot_open_products(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(ProductResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_there_is_no_create_product_route(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get('/admin/products/create')
            ->assertNotFound();
    }
}
