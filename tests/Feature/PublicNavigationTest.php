<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\PublicNavigation;
use Tests\TestCase;

class PublicNavigationTest extends TestCase
{
    public function test_the_frozen_french_navigation_has_eight_sections_and_no_home_item(): void
    {
        app()->setLocale('fr');
        $items = PublicNavigation::items('fr');

        $this->assertCount(8, $items);
        $this->assertSame([
            '/a-propos',
            '/produits',
            '/expertises',
            '/controle-technique',
            '/centres-de-controle',
            '/ressources',
            '/actualites',
            '/contact',
        ], array_column($items, 'url'));
        $this->assertNotContains('home', array_column($items, 'key'));
        $this->assertSame('/recherche', PublicNavigation::searchUrl('fr'));
    }

    public function test_the_frozen_english_navigation_uses_localised_paths_and_labels(): void
    {
        app()->setLocale('en');
        $items = PublicNavigation::items('en');

        $this->assertCount(8, $items);
        $this->assertSame([
            '/en/about',
            '/en/products',
            '/en/expertise',
            '/en/technical-inspection',
            '/en/inspection-centres',
            '/en/resources',
            '/en/news',
            '/en/contact',
        ], array_column($items, 'url'));
        $this->assertSame('Products & Solutions', $items[1]['label']);
        $this->assertSame('/en/search', PublicNavigation::searchUrl('en'));
    }

    public function test_a_section_is_active_for_its_index_and_descendants(): void
    {
        app()->setLocale('fr');
        $items = collect(PublicNavigation::items('fr', 'produits/certidocs-ct'))->keyBy('key');

        $this->assertTrue($items['products']['active']);
        $this->assertFalse($items['about']['active']);

        app()->setLocale('en');
        $items = collect(PublicNavigation::items('en', 'en/inspection-centres/douala'))->keyBy('key');

        $this->assertTrue($items['centres']['active']);
        $this->assertFalse($items['technical_inspection']['active']);
    }

    public function test_the_shared_header_renders_desktop_and_compact_navigation(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('aria-label="Navigation principale"', false)
            ->assertSee('aria-label="Navigation principale mobile"', false)
            ->assertSee('Menu principal')
            ->assertSee('href="/recherche"', false)
            ->assertDontSee('data-primary-navigation-item="home"', false);

        foreach (['about', 'products', 'expertise', 'technical_inspection', 'centres', 'resources', 'news', 'contact'] as $key) {
            $this->assertSame(2, substr_count($response->getContent(), 'data-primary-navigation-item="'.$key.'"'));
        }
    }
}
