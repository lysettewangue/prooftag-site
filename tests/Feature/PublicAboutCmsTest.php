<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\PageCode;
use App\Enums\PublicationStatus;
use App\Models\Page;
use App\Models\Partner;
use App\Models\TeamMember;
use App\Support\ContentCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PublicAboutCmsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        Cache::flush();
    }

    public function test_draft_about_pages_return_not_found(): void
    {
        $this->get('/a-propos')->assertNotFound();
        $this->get('/a-propos/organisation')->assertNotFound();
        $this->get('/a-propos/historique')->assertNotFound();
        $this->get('/a-propos/equipe')->assertNotFound();
        $this->get('/a-propos/partenaires')->assertNotFound();
        $this->get('/en/about')->assertNotFound();
    }

    public function test_published_about_hub_renders_blocks_and_marks_nav_active(): void
    {
        $this->publishPage(PageCode::About, [
            'fr' => [
                'meta_title' => 'À propos — PROOFTAG-CATIS',
                'meta_description' => 'Présentation institutionnelle.',
                'blocks' => [
                    ['type' => 'rich_text', 'content' => 'Récit institutionnel vérifié pour les tests.'],
                    ['type' => 'statistics', 'items' => []],
                ],
            ],
            'en' => [
                'meta_title' => 'About us — PROOFTAG-CATIS',
                'meta_description' => 'Institutional introduction.',
                'blocks' => [
                    ['type' => 'rich_text', 'content' => 'Verified institutional narrative for tests.'],
                ],
            ],
        ]);

        $this->get('/a-propos')
            ->assertOk()
            ->assertSee('<title>À propos — PROOFTAG-CATIS</title>', false)
            ->assertSee('Récit institutionnel vérifié pour les tests.')
            ->assertDontSee('opérateur exclusif')
            ->assertSee('aria-current="page"', false)
            ->assertSee('href="/a-propos"', false);

        $this->get('/en/about')
            ->assertOk()
            ->assertSee('Verified institutional narrative for tests.')
            ->assertDontSee('Récit institutionnel vérifié pour les tests.');
    }

    public function test_child_about_pages_show_breadcrumbs_when_published(): void
    {
        $this->publishPage(PageCode::About);
        $this->publishPage(PageCode::Organisation, [
            'fr' => [
                'blocks' => [
                    ['type' => 'rich_text', 'content' => 'Organisation publiée pour les tests.'],
                ],
            ],
        ]);

        $this->get('/a-propos/organisation')
            ->assertOk()
            ->assertSee('Organisation publiée pour les tests.')
            ->assertSee('À propos')
            ->assertSee('href="/a-propos"', false)
            ->assertSee('Organisation');
    }

    public function test_team_and_partners_pages_use_empty_states_until_members_are_published(): void
    {
        $this->publishPage(PageCode::Team);
        $this->publishPage(PageCode::Partners);

        $this->get('/a-propos/equipe')
            ->assertOk()
            ->assertSee('data-about-team-empty', false)
            ->assertSee('Aucun membre d’équipe n’est encore publié.');

        $this->get('/a-propos/partenaires')
            ->assertOk()
            ->assertSee('data-about-partners-empty', false)
            ->assertSee('Aucun partenaire n’est encore publié.');

        $member = TeamMember::query()->create([
            'full_name' => 'Ada Test',
            'sort_order' => 1,
            'publication_status' => PublicationStatus::Published,
            'published_at' => now(),
        ]);
        $member->translations()->create([
            'locale' => 'fr',
            'slug' => 'ada-test',
            'title' => 'Direction',
            'bio' => 'Profil vérifié.',
        ]);

        $partner = Partner::query()->create([
            'name' => 'Partner Test',
            'sort_order' => 1,
            'url' => null,
            'publication_status' => PublicationStatus::Published,
            'published_at' => now(),
        ]);
        $partner->translations()->create([
            'locale' => 'fr',
            'slug' => 'partner-test',
            'blurb' => 'Partenaire vérifié.',
        ]);

        ContentCache::flush();

        $this->get('/a-propos/equipe')
            ->assertOk()
            ->assertSee('data-about-team', false)
            ->assertSee('Ada Test')
            ->assertSee('Direction')
            ->assertDontSee('data-about-team-empty', false);

        $this->get('/a-propos/partenaires')
            ->assertOk()
            ->assertSee('data-about-partners', false)
            ->assertSee('Partner Test')
            ->assertDontSee('data-about-partners-empty', false);
    }

    public function test_section_nav_lists_only_published_siblings(): void
    {
        $this->publishPage(PageCode::About);
        $this->publishPage(PageCode::History, [
            'fr' => [
                'blocks' => [
                    ['type' => 'timeline', 'items' => [
                        ['year' => '2020', 'text' => 'Point d’historique vérifié.'],
                    ]],
                ],
            ],
        ]);

        $this->get('/a-propos')
            ->assertOk()
            ->assertSee('Dans cette section')
            ->assertSee('href="/a-propos/historique"', false)
            ->assertDontSee('href="/a-propos/organisation"', false);

        $this->get('/a-propos/historique')
            ->assertOk()
            ->assertSee('Point d’historique vérifié.');
    }

    /**
     * @param  array{fr?: array<string, mixed>, en?: array<string, mixed>}  $overrides
     */
    private function publishPage(PageCode $code, array $overrides = []): Page
    {
        $page = Page::query()->where('code', $code)->firstOrFail();
        $page->forceFill([
            'publication_status' => PublicationStatus::Published,
            'published_at' => now(),
        ])->save();

        foreach (['fr', 'en'] as $locale) {
            $payload = $overrides[$locale] ?? [];
            $page->translations()->where('locale', $locale)->update([
                'meta_title' => $payload['meta_title'] ?? null,
                'meta_description' => $payload['meta_description'] ?? null,
                'blocks' => $payload['blocks'] ?? [],
            ]);
        }

        ContentCache::flush();

        return $page->fresh(['translations']);
    }
}
