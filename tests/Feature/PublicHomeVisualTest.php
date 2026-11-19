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
            ->assertSee('home-showcase__visual', false)
            ->assertSee('home/hero/visual.jpg', false)
            ->assertSee('home-pillars', false)
            ->assertSee('home-pillars__icon', false)
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
            ->assertSee('home-products', false)
            ->assertSee('home-process', false)
            ->assertSee('home-centres', false)
            ->assertSee('home-news', false)
            ->assertSee('Écosystème du contrôle technique')
            ->assertSee('Centres de contrôle')
            ->assertSee('Actualités &amp; Articles', false)
            ->assertSee('Besoin d’échanger avec notre équipe ?')
            ->assertSee('Découvrir nos solutions')
            ->assertSee('data-home-products-empty', false)
            ->assertDontSee('Le français est la langue par défaut');

        $this->assertGreaterThanOrEqual(4, substr_count($response->getContent(), 'home-pillars'));
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
            ->assertSee('Technical inspection ecosystem')
            ->assertSee('Inspection centres')
            ->assertSee('Need to talk with our team?')
            ->assertDontSee('Trouver un centre de contrôle');
    }
}
