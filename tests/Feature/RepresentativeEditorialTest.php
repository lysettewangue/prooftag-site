<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\PostKind;
use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepresentativeEditorialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_sample_news_and_articles_stay_draft_and_bilingual(): void
    {
        $this->assertSame(2, Category::query()->count());
        $this->assertSame(2, Post::query()->count());
        $this->assertTrue(Post::query()->where('publication_status', '!=', PublicationStatus::Draft)->doesntExist());
        $this->assertTrue(Category::query()->where('publication_status', '!=', PublicationStatus::Draft)->doesntExist());

        $news = Post::query()->where('kind', PostKind::News)->firstOrFail();
        $article = Post::query()->where('kind', PostKind::Article)->firstOrFail();

        $this->assertNull($news->author_id);
        $this->assertSame('reconstruction-du-site-corporate', $news->translationFor('fr')?->slug);
        $this->assertSame('corporate-website-rebuild', $news->translationFor('en')?->slug);
        $this->assertSame('themes-controle-technique-securise', $article->translationFor('fr')?->slug);
        $this->assertSame('secured-technical-inspection-themes', $article->translationFor('en')?->slug);

        $certidocs = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();
        $this->assertTrue($article->products()->whereKey($certidocs->id)->exists());
        $this->assertFalse($news->products()->exists());
        $this->assertTrue($article->categories()->whereHas('translations', function ($query): void {
            $query->where('locale', 'fr')->where('slug', 'controle-technique');
        })->exists());
    }
}
