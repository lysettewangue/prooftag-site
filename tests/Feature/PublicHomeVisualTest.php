<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicHomeVisualTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SiteSettingSeeder::class);
    }

    public function test_the_french_home_has_the_complete_visual_narrative(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('public-home-hero-visual', false)
            ->assertSee('home/hero/visual.jpg', false)
            ->assertSee('public-home-pillars', false)
            ->assertSee('public-home-pillar-icon', false)
            ->assertDontSee('data-home-hero-slideshow')
            ->assertDontSee('homeHeroSlideshow')
            ->assertDontSee('home-hero-visual.jpg')
            ->assertDontSee('public-home-hero-panel-dot')
            ->assertSee('PROOFTAG-CATIS')
            ->assertSee('Des solutions numériques pour le transport, le contrôle technique et la sécurité routière')
            ->assertSee('À propos de nous')
            ->assertSee('Technologies de transport')
            ->assertSee('Sécurité documentaire')
            ->assertSee('Analyse & reporting')
            ->assertSee('Prévention routière')
            ->assertSee('Produits & Solutions')
            ->assertSee('Découvrir tous nos produits')
            ->assertSee('En savoir plus')
            ->assertSee('public-home-products', false)
            ->assertSee('Que souhaitez-vous faire ?')
            ->assertSee('Technologie au service de la sécurité routière')
            ->assertSee('Besoin d’échanger avec notre équipe ?')
            ->assertSee('Découvrir nos solutions')
            ->assertSee('data-home-products-empty', false)
            ->assertDontSee('Le français est la langue par défaut');

        $this->assertGreaterThanOrEqual(4, substr_count($response->getContent(), 'public-home-pillar'));
        $this->assertStringContainsString('chart-column', $response->getContent());
        $this->assertStringContainsString('cone', $response->getContent());
    }

    public function test_the_english_home_is_fully_localised(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('Digital solutions for transport, technical inspection and road safety')
            ->assertSee('About us')
            ->assertSee('Transport technologies')
            ->assertSee('Products & Solutions')
            ->assertSee('Discover all our products')
            ->assertSee('What would you like to do?')
            ->assertSee('Technology in service of road safety')
            ->assertSee('Need to talk with our team?')
            ->assertDontSee('Que souhaitez-vous faire ?');
    }
}
