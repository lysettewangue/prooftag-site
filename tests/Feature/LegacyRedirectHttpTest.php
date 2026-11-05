<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegacyRedirectHttpTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_site_ditros_redirects_to_the_ct_hub_not_certidocs(): void
    {
        $this->get('http://site.prooftagcatis.com/solutions/ditros-ct')
            ->assertRedirect('https://www.prooftagcatis.com/controle-technique')
            ->assertStatus(301);
    }

    public function test_www_services_redirects_to_certidocs_ct(): void
    {
        $this->get('http://www.prooftagcatis.com/services')
            ->assertRedirect('https://www.prooftagcatis.com/produits/certidocs-ct')
            ->assertStatus(301);
    }

    public function test_apex_homepage_retires_to_www(): void
    {
        $this->get('http://prooftagcatis.com/')
            ->assertRedirect('https://www.prooftagcatis.com/')
            ->assertStatus(301);
    }

    public function test_local_host_without_legacy_match_is_unchanged(): void
    {
        $this->get('/contact')->assertOk();
    }
}
