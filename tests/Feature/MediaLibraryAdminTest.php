<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Media\AttachMedia;
use App\Actions\Media\SafeDeleteMedia;
use App\Actions\Media\TranslateMedia;
use App\Actions\Media\UpdateMedia;
use App\Actions\Products\ApproveProduct;
use App\Actions\Products\PublishProduct;
use App\Actions\Products\SubmitProductForReview;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\ProductCode;
use App\Enums\StaffRole;
use App\Filament\Resources\MediaLibrary\MediaLibraryResource;
use App\Models\Media;
use App\Models\Product;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaLibraryAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
        Storage::fake('public');
    }

    public function test_empty_media_list_is_valid(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(MediaLibraryResource::getUrl('index'))
            ->assertOk();

        $this->assertSame(0, Media::query()->count());
        $this->assertFalse(MediaLibraryResource::canCreate());
        $this->assertFalse(MediaLibraryResource::canDeleteAny());
    }

    public function test_translator_can_list_but_cannot_create(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(MediaLibraryResource::getUrl('index'))
            ->assertOk();

        $this->assertFalse(MediaLibraryResource::canCreate());
    }

    public function test_content_manager_can_list(): void
    {
        $manager = User::factory()->staff(StaffRole::ContentManager)->create();

        $this->actingAs($manager)
            ->get(MediaLibraryResource::getUrl('index'))
            ->assertOk();
    }

    public function test_technical_editor_cannot_open_media_library(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $this->actingAs($editor)
            ->get(MediaLibraryResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_communications_editor_cannot_open_media_library(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(MediaLibraryResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_reviewer_cannot_open_media_library(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(MediaLibraryResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_support_cannot_open_media_library(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(MediaLibraryResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_admin_can_update_french_alt_and_rights(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $media = $this->attachProductImage($admin);

        (new UpdateMedia)->handle($media, $admin, [
            'alt.fr' => 'Logo produit',
            'rights' => 'PROOFTAG-CATIS',
        ]);

        $media->refresh();

        $this->assertSame('Logo produit', $media->altFr());
        $this->assertSame('Product', $media->altEn());
        $this->assertSame('PROOFTAG-CATIS', $media->rights());

        $this->actingAs($admin)
            ->get(MediaLibraryResource::getUrl('edit', ['record' => $media]))
            ->assertOk();
    }

    public function test_translator_can_write_english_alt_and_open_edit(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $media = $this->attachProductImage($admin);

        (new TranslateMedia)->handle($media, $translator, [
            'alt.en' => 'Product logo',
        ]);

        $this->assertSame('Product logo', $media->fresh()?->altEn());
        $this->assertSame('Produit', $media->fresh()?->altFr());

        $this->actingAs($translator)
            ->get(MediaLibraryResource::getUrl('edit', ['record' => $media]))
            ->assertOk();
    }

    public function test_translator_cannot_update_rights(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $media = $this->attachProductImage($admin);

        $this->expectException(AuthorizationException::class);

        (new UpdateMedia)->handle($media, $translator, [
            'rights' => 'stolen',
        ]);
    }

    public function test_super_admin_cannot_create_orphan_media(): void
    {
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $this->actingAs($super);

        $this->assertFalse(MediaLibraryResource::canCreate());
    }

    public function test_unused_media_can_be_deleted(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $media = $this->attachProductImage($admin);

        $this->actingAs($admin);
        $this->assertTrue(MediaLibraryResource::canDelete($media));

        (new SafeDeleteMedia)->handle($media, $admin);

        $this->assertNull(Media::query()->find($media->id));
    }

    public function test_media_on_a_public_owner_cannot_be_deleted(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $media = $this->attachProductImage($admin);
        $product = Product::query()->findOrFail($media->model_id);

        (new SubmitProductForReview)->handle($product, $admin);
        (new ApproveProduct)->handle($product, $admin);
        (new PublishProduct)->handle($product->fresh(), $admin);

        $this->expectException(DomainRuleViolation::class);
        (new SafeDeleteMedia)->handle($media->fresh(), $admin);
    }

    public function test_clearing_image_alt_is_rejected(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $media = $this->attachProductImage($admin);

        $this->expectException(DomainRuleViolation::class);

        (new UpdateMedia)->handle($media, $admin, [
            'alt.fr' => '',
        ]);
    }

    public function test_pdf_does_not_require_alt(): void
    {
        Storage::fake('documents');

        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();
        $file = UploadedFile::fake()->create('note.pdf', 20, 'application/pdf');

        $media = (new AttachMedia)->handle($product, $admin, $file, 'images', [
            'rights' => '',
        ]);

        $this->assertInstanceOf(Media::class, $media);
        $this->assertSame('', $media->altFr());
        $this->assertSame('', $media->rights());
    }

    private function attachProductImage(User $actor): Media
    {
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();
        $file = UploadedFile::fake()->image('hero.jpg');

        return (new AttachMedia)->handle($product, $actor, $file, 'images', [
            'alt.fr' => 'Produit',
            'alt.en' => 'Product',
            'rights' => 'internal',
        ]);
    }
}
