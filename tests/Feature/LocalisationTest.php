<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class LocalisationTest extends TestCase
{
    public function test_french_home_has_no_locale_prefix(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('lang="fr"', false)
            ->assertSee('Le français est la langue par défaut', false)
            ->assertDontSee('This page is not a French copy', false);
    }

    public function test_english_home_uses_en_prefix_and_english_copy(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('lang="en"', false)
            ->assertSee('English is the secondary locale', false)
            ->assertDontSee('Cette page n’est pas une copie anglaise', false);
    }

    public function test_fr_prefix_redirects_to_unprefixed_french(): void
    {
        $this->get('/fr')->assertRedirect('/');
        $this->get('/fr/')->assertRedirect('/');
    }

    public function test_admin_is_not_inside_the_english_prefix(): void
    {
        $this->get('/en/admin')->assertNotFound();
    }
}
