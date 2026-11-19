<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\PostKind;
use App\Enums\PublicationStatus;
use App\Models\Category;
use App\Models\Post;
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

    public function test_unverified_sample_articles_are_removed_and_news_draft_stays_private(): void
    {
        $this->assertSame(2, Category::query()->count());
        $this->assertSame(1, Post::query()->count());
        $this->assertTrue(Post::query()->where('publication_status', '!=', PublicationStatus::Draft)->doesntExist());
        $this->assertTrue(Category::query()->where('publication_status', '!=', PublicationStatus::Draft)->doesntExist());

        $news = Post::query()->where('kind', PostKind::News)->firstOrFail();

        $this->assertNull($news->author_id);
        $this->assertSame('reconstruction-du-site-corporate', $news->translationFor('fr')?->slug);
        $this->assertSame('corporate-website-rebuild', $news->translationFor('en')?->slug);
        $this->assertTrue(Post::query()->where('kind', PostKind::Article)->doesntExist());
        $this->assertFalse($news->products()->exists());
        $this->assertTrue(Post::query()->whereHas('translations', function ($query): void {
            $query->whereIn('slug', [
                'themes-controle-technique-securise',
                'secured-technical-inspection-themes',
            ]);
        })->doesntExist());
    }
}
