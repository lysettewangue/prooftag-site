<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Corporate\TranslatePage;
use App\Actions\Editorial\ApprovePost;
use App\Actions\Editorial\CreatePost;
use App\Actions\Editorial\PublishPost;
use App\Actions\Editorial\SubmitPostForReview;
use App\Actions\Editorial\TranslatePost;
use App\Actions\Products\TranslateProduct;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PageCode;
use App\Enums\PostKind;
use App\Enums\ProductCode;
use App\Enums\StaffRole;
use App\Filament\Resources\Posts\PostResource;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use App\Services\Seo\SeoMetaResolver;
use App\Support\PublicPath;
use DateTimeImmutable;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_sample_posts_keep_independent_french_and_english_slugs(): void
    {
        $news = Post::query()->where('kind', PostKind::News)->firstOrFail();

        $this->assertSame('reconstruction-du-site-corporate', $news->translationFor('fr')?->slug);
        $this->assertSame('corporate-website-rebuild', $news->translationFor('en')?->slug);
        $this->assertSame([], $news->missingLocales());
    }

    public function test_product_and_page_slugs_stay_frozen(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();
        $about = Page::query()->where('code', PageCode::About)->firstOrFail();

        try {
            (new TranslateProduct)->handle($product, $admin, [
                'locale' => 'en',
                'slug' => 'hacked',
                'summary' => 'Rewrite required.',
            ]);
            $this->fail('Product slug change must be rejected.');
        } catch (DomainRuleViolation $exception) {
            $this->assertStringContainsString('frozen fragment', $exception->getMessage());
        }

        try {
            (new TranslatePage)->handle($about, $admin, [
                'locale' => 'en',
                'slug' => 'hacked',
                'title' => 'About',
            ]);
            $this->fail('Page slug change must be rejected.');
        } catch (DomainRuleViolation $exception) {
            $this->assertStringContainsString('frozen', $exception->getMessage());
        }
    }

    public function test_missing_english_hides_the_english_url_after_publish(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $now = new DateTimeImmutable;

        $post = (new CreatePost)->handle($admin, ['kind' => PostKind::News->value]);
        (new TranslatePost)->handle($post, $admin, [
            'locale' => 'fr',
            'title' => 'Actualité FR seulement',
            'slug' => 'actualite-fr-seulement',
        ]);

        $this->assertSame(['en'], $post->missingLocales());
        $this->assertNull(PublicPath::for($post, 'en'));
        $this->assertSame('/actualites/actualite-fr-seulement', PublicPath::for($post, 'fr'));

        (new SubmitPostForReview)->handle($post, $admin);
        (new ApprovePost)->handle($post, $admin);
        (new PublishPost)->handle($post->fresh(), $admin);

        $published = $post->fresh();
        $this->assertNotNull($published);
        $this->assertTrue($published->isPubliclyVisible($now));
        $this->assertTrue($published->isPubliclyVisibleIn('fr', $now));
        $this->assertFalse($published->isPubliclyVisibleIn('en', $now));

        $meta = (new SeoMetaResolver)->forModel($published, 'fr');
        $this->assertArrayHasKey('fr', $meta->hreflang);
        $this->assertArrayNotHasKey('en', $meta->hreflang);
    }

    public function test_adding_english_unlocks_an_independent_slug_and_hreflang(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $now = new DateTimeImmutable;

        $post = (new CreatePost)->handle($admin, ['kind' => PostKind::Article->value]);
        (new TranslatePost)->handle($post, $admin, [
            'locale' => 'fr',
            'title' => 'Article bilingue',
            'slug' => 'article-bilingue',
        ]);
        (new TranslatePost)->handle($post, $admin, [
            'locale' => 'en',
            'title' => 'Bilingual article',
            'slug' => 'bilingual-article',
        ]);
        (new SubmitPostForReview)->handle($post, $admin);
        (new ApprovePost)->handle($post, $admin);
        (new PublishPost)->handle($post->fresh(), $admin);

        $published = $post->fresh();
        $this->assertNotNull($published);
        $this->assertSame([], $published->missingLocales());
        $this->assertTrue($published->isPubliclyVisibleIn('en', $now));
        $this->assertSame('/en/news/bilingual-article', PublicPath::for($published, 'en'));

        $meta = (new SeoMetaResolver)->forModel($published, 'en');
        $this->assertArrayHasKey('fr', $meta->hreflang);
        $this->assertArrayHasKey('en', $meta->hreflang);
    }

    public function test_drafts_stay_hidden_even_with_both_locales(): void
    {
        $now = new DateTimeImmutable;
        $news = Post::query()->where('kind', PostKind::News)->firstOrFail();

        $this->assertSame([], $news->missingLocales());
        $this->assertFalse($news->isPubliclyVisible($now));
        $this->assertFalse($news->isPubliclyVisibleIn('fr', $now));
        $this->assertFalse($news->isPubliclyVisibleIn('en', $now));
    }

    public function test_communications_editor_opens_french_and_translator_opens_english(): void
    {
        $post = Post::query()->where('kind', PostKind::News)->firstOrFail();

        $this->actingAs(User::factory()->staff(StaffRole::CommunicationsEditor)->create())
            ->get(PostResource::getUrl('edit', ['record' => $post]))
            ->assertOk();

        $this->actingAs(User::factory()->staff(StaffRole::Translator)->create())
            ->get(PostResource::getUrl('edit', ['record' => $post]))
            ->assertOk();
    }
}
