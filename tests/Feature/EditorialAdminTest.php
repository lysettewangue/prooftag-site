<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Editorial\CreateCategory;
use App\Actions\Editorial\CreatePost;
use App\Actions\Editorial\TranslateCategory;
use App\Actions\Editorial\TranslatePost;
use App\Actions\Editorial\UpdatePost;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PostKind;
use App\Enums\PublicationStatus;
use App\Enums\StaffRole;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Posts\PostResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditorialAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_empty_editorial_lists_are_valid(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(PostResource::getUrl('index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(CategoryResource::getUrl('index'))
            ->assertOk();

        $this->assertSame(0, Post::query()->count());
        $this->assertSame(0, Category::query()->count());
        $this->assertTrue(PostResource::canCreate());
        $this->assertTrue(CategoryResource::canCreate());
        $this->assertFalse(PostResource::canDeleteAny());
        $this->assertFalse(CategoryResource::canDeleteAny());
    }

    public function test_communications_editor_can_open_create_pages(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(PostResource::getUrl('create'))
            ->assertOk();

        $this->actingAs($editor)
            ->get(CategoryResource::getUrl('create'))
            ->assertOk();
    }

    public function test_translator_can_list_but_cannot_create(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(PostResource::getUrl('index'))
            ->assertOk();

        $this->actingAs($translator)
            ->get(CategoryResource::getUrl('index'))
            ->assertOk();

        $this->assertFalse(PostResource::canCreate());
        $this->assertFalse(CategoryResource::canCreate());

        $this->actingAs($translator)
            ->get(PostResource::getUrl('create'))
            ->assertForbidden();
    }

    public function test_reviewer_can_open_editorial(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(PostResource::getUrl('index'))
            ->assertOk();

        $this->actingAs($reviewer)
            ->get(CategoryResource::getUrl('index'))
            ->assertOk();
    }

    public function test_technical_editor_cannot_open_editorial(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $this->actingAs($editor)
            ->get(PostResource::getUrl('index'))
            ->assertForbidden();

        $this->actingAs($editor)
            ->get(CategoryResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_support_cannot_open_editorial(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(PostResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_communications_editor_can_create_french_drafts(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $post = $this->makePost($editor);
        $category = $this->makeCategory($editor);

        $this->assertSame(PublicationStatus::Draft, $post->publication_status);
        $this->assertSame(PostKind::News, $post->kind);
        $this->assertSame('Nouvelle CT', $post->translationFor('fr')?->title);
        $this->assertSame('nouvelle-ct', $post->translationFor('fr')?->slug);
        $this->assertSame($editor->id, $post->author_id);

        $this->assertSame(PublicationStatus::Draft, $category->publication_status);
        $this->assertSame('Réglementation', $category->translationFor('fr')?->name);
        $this->assertSame('reglementation', $category->translationFor('fr')?->slug);
    }

    public function test_communications_editor_cannot_write_english(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $post = $this->makePost($editor);

        $this->expectException(AuthorizationException::class);

        (new TranslatePost)->handle($post, $editor, [
            'locale' => 'en',
            'title' => 'CT news',
        ]);
    }

    public function test_translator_can_write_english_and_open_edit(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $post = $this->makePost($editor);

        (new TranslatePost)->handle($post, $translator, [
            'locale' => 'en',
            'title' => 'CT news',
        ]);

        $this->assertSame('CT news', $post->translationFor('en')?->title);
        $this->assertSame('ct-news', $post->translationFor('en')?->slug);

        $this->actingAs($translator)
            ->get(PostResource::getUrl('edit', ['record' => $post]))
            ->assertOk();
    }

    public function test_post_and_category_cannot_be_hard_deleted(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $post = $this->makePost($admin);
        $category = $this->makeCategory($admin);

        $this->assertFalse($admin->can('delete', $post));
        $this->assertFalse($admin->can('delete', $category));
        $this->assertFalse(PostResource::canDelete($post));
        $this->assertFalse(CategoryResource::canDelete($category));

        $this->actingAs($super);
        $this->assertFalse(PostResource::canDelete($post));
        $this->assertFalse(PostResource::canForceDelete($post));
        $this->assertFalse(CategoryResource::canForceDelete($category));
    }

    public function test_post_slugs_must_be_unique_per_locale(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $this->makePost($editor);
        $second = (new CreatePost)->handle($editor, ['kind' => PostKind::Article->value]);

        $this->expectException(DomainRuleViolation::class);

        (new TranslatePost)->handle($second, $editor, [
            'locale' => 'fr',
            'title' => 'Autre titre',
            'slug' => 'nouvelle-ct',
        ]);
    }

    public function test_post_can_be_tagged_with_category_and_product(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $post = $this->makePost($editor);
        $category = $this->makeCategory($editor);
        $product = Product::query()->firstOrFail();

        (new UpdatePost)->handle($post, $editor, [
            'category_ids' => [$category->id],
            'product_ids' => [$product->id],
        ]);

        $this->assertTrue($post->categories()->whereKey($category->id)->exists());
        $this->assertTrue($post->products()->whereKey($product->id)->exists());
    }

    public function test_french_and_english_post_slugs_may_differ(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $post = $this->makePost($admin);

        (new TranslatePost)->handle($post, $admin, [
            'locale' => 'en',
            'title' => 'Inspection news',
            'slug' => 'inspection-news',
        ]);

        $this->assertSame('nouvelle-ct', $post->translationFor('fr')?->slug);
        $this->assertSame('inspection-news', $post->translationFor('en')?->slug);
    }

    private function makePost(User $actor): Post
    {
        $post = (new CreatePost)->handle($actor, ['kind' => PostKind::News->value]);

        (new TranslatePost)->handle($post, $actor, [
            'locale' => 'fr',
            'title' => 'Nouvelle CT',
        ]);

        return $post;
    }

    private function makeCategory(User $actor): Category
    {
        $category = (new CreateCategory)->handle($actor, ['sort_order' => 1]);

        (new TranslateCategory)->handle($category, $actor, [
            'locale' => 'fr',
            'name' => 'Réglementation',
        ]);

        return $category;
    }
}
