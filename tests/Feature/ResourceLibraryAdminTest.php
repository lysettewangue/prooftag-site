<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Media\AttachMedia;
use App\Actions\Resources\ApproveResource;
use App\Actions\Resources\CreateResource;
use App\Actions\Resources\PublishResource;
use App\Actions\Resources\SubmitResourceForReview;
use App\Actions\Resources\TranslateResource;
use App\Actions\Resources\UpdateResource;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PublicationStatus;
use App\Enums\ResourceRegulatoryStatus;
use App\Enums\StaffRole;
use App\Filament\Resources\LibraryDocuments\LibraryDocumentResource;
use App\Models\Product;
use App\Models\Resource;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResourceLibraryAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_representative_historical_resource_is_seeded_without_pdf(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(LibraryDocumentResource::getUrl('index'))
            ->assertOk();

        $this->assertSame(1, Resource::query()->count());
        $resource = Resource::query()->firstOrFail();
        $this->assertSame(ResourceRegulatoryStatus::Historical, $resource->regulatory_status);
        $this->assertSame(PublicationStatus::Draft, $resource->publication_status);
        $this->assertFalse($resource->mayEnterPublished());
        $this->assertTrue(LibraryDocumentResource::canCreate());
        $this->assertFalse(LibraryDocumentResource::canDeleteAny());
    }

    public function test_technical_editor_can_open_create(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $this->actingAs($editor)
            ->get(LibraryDocumentResource::getUrl('create'))
            ->assertOk();
    }

    public function test_translator_can_list_but_cannot_create(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(LibraryDocumentResource::getUrl('index'))
            ->assertOk();

        $this->assertFalse(LibraryDocumentResource::canCreate());

        $this->actingAs($translator)
            ->get(LibraryDocumentResource::getUrl('create'))
            ->assertForbidden();
    }

    public function test_reviewer_can_open_resources(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(LibraryDocumentResource::getUrl('index'))
            ->assertOk();
    }

    public function test_communications_editor_cannot_open_resources(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(LibraryDocumentResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_support_cannot_open_resources(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(LibraryDocumentResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_technical_editor_can_create_french_draft(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $resource = $this->makeResource($editor);

        $this->assertSame(PublicationStatus::Draft, $resource->publication_status);
        $this->assertSame(ResourceRegulatoryStatus::Current, $resource->regulatory_status);
        $this->assertSame('Guide CT', $resource->translationFor('fr')?->title);
        $this->assertSame('guide-ct', $resource->translationFor('fr')?->slug);
        $this->assertSame('MINTRANSPORT', $resource->authority);
        $this->assertFalse($resource->relates_to_ct);
    }

    public function test_technical_editor_cannot_write_english(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $resource = $this->makeResource($editor);

        $this->expectException(AuthorizationException::class);

        (new TranslateResource)->handle($resource, $editor, [
            'locale' => 'en',
            'title' => 'CT guide',
        ]);
    }

    public function test_translator_can_write_english_and_open_edit(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $resource = $this->makeResource($editor);

        (new TranslateResource)->handle($resource, $translator, [
            'locale' => 'en',
            'title' => 'CT guide',
        ]);

        $this->assertSame('CT guide', $resource->translationFor('en')?->title);
        $this->assertSame('ct-guide', $resource->translationFor('en')?->slug);

        $this->actingAs($translator)
            ->get(LibraryDocumentResource::getUrl('edit', ['record' => $resource]))
            ->assertOk();
    }

    public function test_resource_cannot_be_hard_deleted(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $resource = $this->makeResource($admin);

        $this->assertFalse($admin->can('delete', $resource));
        $this->assertFalse(LibraryDocumentResource::canDelete($resource));

        $this->actingAs($super);
        $this->assertFalse(LibraryDocumentResource::canDelete($resource));
        $this->assertFalse(LibraryDocumentResource::canForceDelete($resource));
    }

    public function test_resource_slugs_must_be_unique_per_locale(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $this->makeResource($editor);
        $second = (new CreateResource)->handle($editor, [
            'regulatory_status' => ResourceRegulatoryStatus::Historical->value,
        ]);

        $this->expectException(DomainRuleViolation::class);

        (new TranslateResource)->handle($second, $editor, [
            'locale' => 'fr',
            'title' => 'Autre guide',
            'slug' => 'guide-ct',
        ]);
    }

    public function test_archive_status_is_a_label_not_soft_delete(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $resource = $this->makeResource($editor);

        (new UpdateResource)->handle($resource, $editor, [
            'regulatory_status' => ResourceRegulatoryStatus::Archive->value,
        ]);

        $this->assertSame(ResourceRegulatoryStatus::Archive, $resource->fresh()->regulatory_status);
        $this->assertNull($resource->fresh()->deleted_at);
        $this->assertSame(PublicationStatus::Draft, $resource->fresh()->publication_status);
    }

    public function test_resource_can_be_tagged_with_a_product(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $resource = $this->makeResource($editor);
        $product = Product::query()->firstOrFail();

        (new UpdateResource)->handle($resource, $editor, [
            'product_ids' => [$product->id],
        ]);

        $this->assertTrue($resource->products()->whereKey($product->id)->exists());
    }

    public function test_publish_requires_a_document_file(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $resource = $this->makeResource($admin);

        $this->assertFalse($resource->mayEnterPublished());

        (new SubmitResourceForReview)->handle($resource, $admin);
        (new ApproveResource)->handle($resource, $admin);

        $this->expectException(DomainRuleViolation::class);

        (new PublishResource)->handle($resource->fresh(), $admin);
    }

    public function test_attached_pdf_allows_publish_check(): void
    {
        Storage::fake('documents');

        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $resource = $this->makeResource($admin);

        (new AttachMedia)->handle(
            $resource,
            $admin,
            UploadedFile::fake()->create('guide.pdf', 120, 'application/pdf'),
            'documents',
            ['rights' => 'PROOFTAG-CATIS'],
        );

        $this->assertTrue($resource->fresh()->mayEnterPublished());
    }

    private function makeResource(User $actor): Resource
    {
        $resource = (new CreateResource)->handle($actor, [
            'regulatory_status' => ResourceRegulatoryStatus::Current->value,
            'authority' => 'MINTRANSPORT',
        ]);

        (new TranslateResource)->handle($resource, $actor, [
            'locale' => 'fr',
            'title' => 'Guide CT',
        ]);

        return $resource;
    }
}
