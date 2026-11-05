<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PublicTechnicalInspectionHubTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_hub_and_security_points_render(): void
    {
        $this->seed(\Database\Seeders\PageSeeder::class);
        $this->seed(\Database\Seeders\TechnicalInspectionContentMigrationSeeder::class);
        $this->seed(\Database\Seeders\EquipmentContentMigrationSeeder::class);

        $this->get('/controle-technique')
            ->assertOk()
            ->assertSee('Contrôle technique')
            ->assertSee('Bancs, serveur, PV')
            ->assertSee('Comment le système fonctionne')
            ->assertSee('Au centre de contrôle')
            ->assertSee('scellé à bulles', false)
            ->assertSee('Supervision nationale')
            ->assertSee('Contrôles de terrain')
            ->assertSee('Schéma du processus sécurisé')
            ->assertSee('Les 12 points de sécurisation')
            ->assertSee('Matériels du processus')
            ->assertSee('Banc de tests')
            ->assertSee('Douchette')
            ->assertDontSee('AUD-006')
            ->assertDontSee('source www')
            ->assertDontSee('Workflow documenté en 2020')
            ->assertSee('data-ct-points', false)
            ->assertSee('data-security-points', false)
            ->assertSee('data-ct-equipment', false)
            ->assertSee('/images/content/technical-inspection/hub.jpg', false)
            ->assertSee('/images/content/technical-inspection/schema-12-points.jpg', false)
            ->assertSee('/images/content/technical-inspection/process/pv-vignette.jpg', false)
            ->assertSee('/images/content/technical-inspection/scelle-bulles.jpg', false)
            ->assertSee('/images/content/technical-inspection/equipment/server.jpg', false)
            ->assertSee('/images/content/technical-inspection/equipment/douchette.jpg', false)
            ->assertSee('/images/content/technical-inspection/process/field.jpg', false);

        $this->get('/controle-technique/points-de-securisation')
            ->assertOk()
            ->assertSee('Douze points du processus')
            ->assertSee('data-security-points', false);
    }

    public function test_draft_hub_returns_not_found(): void
    {
        $this->seed(\Database\Seeders\PageSeeder::class);

        $this->get('/controle-technique')->assertNotFound();
    }
}
