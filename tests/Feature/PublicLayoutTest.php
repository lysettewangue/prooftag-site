<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PublicLayoutTest extends TestCase
{
    public function test_the_french_home_uses_the_shared_public_shell(): void
    {
        $frenchHome = route('fr.home');
        $englishHome = route('en.home');

        $this->get('/')
            ->assertOk()
            ->assertSee('lang="fr"', false)
            ->assertSee('data-public-shell="true"', false)
            ->assertSee('data-public-shell-region="header"', false)
            ->assertSee('data-public-shell-region="footer"', false)
            ->assertSee('href="#main-content"', false)
            ->assertSee('id="main-content"', false)
            ->assertSee('Aller au contenu')
            ->assertSee('aria-label="PROOFTAG-CATIS, accueil français"', false)
            ->assertSee('rel="canonical" href="'.$frenchHome.'"', false)
            ->assertSee('hreflang="en" href="'.$englishHome.'"', false)
            ->assertSee('images/brand/logo.png', false);
    }

    public function test_the_english_home_localises_the_shared_shell(): void
    {
        $frenchHome = route('fr.home');
        $englishHome = route('en.home');

        $this->get('/en')
            ->assertOk()
            ->assertSee('lang="en"', false)
            ->assertSee('Skip to content')
            ->assertSee('aria-label="PROOFTAG-CATIS, English home"', false)
            ->assertSee('aria-label="Choose language"', false)
            ->assertSee('rel="canonical" href="'.$englishHome.'"', false)
            ->assertSee('hreflang="fr" href="'.$frenchHome.'"', false)
            ->assertSee('Technology, transport and road safety');
    }
}
