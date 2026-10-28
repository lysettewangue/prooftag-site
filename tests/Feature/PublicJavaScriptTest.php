<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicJavaScriptTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SiteSettingSeeder::class);
    }

    public function test_the_public_shell_exposes_the_javascript_foundation_hooks(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('name="csrf-token"', false)
            ->assertSee('content="'.csrf_token().'"', false)
            ->assertSee('data-public-header', false)
            ->assertSee('data-public-mobile-nav', false)
            ->assertSee('resources/js/app.js', false);
    }

    public function test_the_javascript_entry_wires_vanilla_boot_and_alpine(): void
    {
        $entry = (string) file_get_contents(resource_path('js/app.js'));

        $this->assertStringContainsString("import './bootstrap'", $entry);
        $this->assertStringContainsString("from './public/boot'", $entry);
        $this->assertStringContainsString("from './public/alpine'", $entry);
        $this->assertStringContainsString('bootPublicSite()', $entry);
        $this->assertStringContainsString('startAlpine()', $entry);

        $alpine = (string) file_get_contents(resource_path('js/public/alpine.js'));
        $this->assertStringContainsString("from 'alpinejs'", $alpine);
        $this->assertStringContainsString('Alpine.start()', $alpine);

        $header = (string) file_get_contents(resource_path('js/public/header.js'));
        $this->assertStringContainsString('data-public-header', $header);
        $this->assertStringContainsString('data-public-mobile-nav', $header);
        $this->assertStringContainsString("Escape", $header);
    }
}
