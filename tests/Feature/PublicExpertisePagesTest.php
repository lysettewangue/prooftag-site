<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ExpertiseCode;
use App\Enums\PublicationStatus;
use App\Models\Expertise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicExpertisePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_expertise_index_and_detail_render(): void
    {
        $this->seed(\Database\Seeders\ExpertiseSeeder::class);
        $this->seed(\Database\Seeders\ExpertiseContentMigrationSeeder::class);

        $this->get('/expertises')
            ->assertOk()
            ->assertSee('data-expertise-index', false)
            ->assertSee('Recherche et développement')
            ->assertSee('Sécurité documentaire');

        $this->get('/expertises/securite-documentaire')
            ->assertOk()
            ->assertSee('Sécurité documentaire')
            ->assertSee('Points clés')
            ->assertSee('L’unicité du document')
            ->assertSee('Toutes les expertises');
    }

    public function test_draft_expertise_detail_returns_not_found(): void
    {
        $expertise = Expertise::query()->create([
            'code' => ExpertiseCode::ResearchDevelopment,
            'sort_order' => 1,
            'publication_status' => PublicationStatus::Draft,
        ]);
        $expertise->translations()->create([
            'locale' => 'fr',
            'slug' => 'recherche-et-developpement',
            'title' => 'Recherche et développement',
            'summary' => 'Résumé draft.',
            'body' => ['html' => '<p>Corps draft.</p>'],
        ]);

        $this->get('/expertises/recherche-et-developpement')->assertNotFound();
    }
}
