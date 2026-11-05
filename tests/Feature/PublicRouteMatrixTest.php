<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRouteMatrixTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_migrated_french_routes_return_ok(): void
    {
        foreach ([
            '/',
            '/a-propos',
            '/produits',
            '/produits/certidocs-ct',
            '/controle-technique',
            '/centres-de-controle',
            '/ressources',
            '/actualites',
            '/contact',
            '/expertises',
        ] as $path) {
            $response = $this->get($path);
            $this->assertTrue($response->isOk(), "Expected 200 for {$path}, got {$response->status()}");
        }
    }

    public function test_migrated_english_routes_return_ok(): void
    {
        foreach ([
            '/en',
            '/en/about',
            '/en/products',
            '/en/products/certidocs-ct',
            '/en/technical-inspection',
            '/en/inspection-centres',
            '/en/resources',
            '/en/news',
            '/en/contact',
            '/en/expertise',
        ] as $path) {
            $response = $this->get($path);
            $this->assertTrue($response->isOk(), "Expected 200 for {$path}, got {$response->status()}");
        }
    }

    public function test_draft_gated_products_stay_not_found(): void
    {
        $this->get('/produits/ct-verif')->assertNotFound();
        $this->get('/produits/doser')->assertNotFound();
        $this->get('/produits/ditros-ct')->assertNotFound();
        $this->get('/en/products/ct-verif')->assertNotFound();
    }
}
