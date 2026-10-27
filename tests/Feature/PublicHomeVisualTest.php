<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PublicHomeVisualTest extends TestCase
{
    public function test_the_french_home_has_the_complete_visual_narrative(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('public-hero-mesh', false)
            ->assertSee('Des solutions numériques au service de routes plus sûres.')
            ->assertSee('Une plateforme claire pour comprendre notre action.')
            ->assertSee('Une expérience institutionnelle, rendue simple.')
            ->assertSee('Avançons vers une mobilité mieux sécurisée.')
            ->assertSee('public-network-panel', false)
            ->assertSee('Découvrir nos solutions')
            ->assertDontSee('Le français est la langue par défaut');

        $this->assertSame(3, substr_count($response->getContent(), 'group overflow-hidden'));
    }

    public function test_the_english_home_is_fully_localised(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('Digital solutions for safer roads.')
            ->assertSee('A clear platform for understanding what we do.')
            ->assertSee('An institutional experience, made simple.')
            ->assertSee('Let us move towards safer mobility.')
            ->assertDontSee('Une plateforme claire pour comprendre notre action.');
    }
}
