<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\LucideIcon;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use InvalidArgumentException;
use Tests\TestCase;

class PublicLucideTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SiteSettingSeeder::class);
    }

    public function test_the_curated_lucide_set_is_available_to_blade(): void
    {
        $names = LucideIcon::names();

        $this->assertContains('search', $names);
        $this->assertContains('phone', $names);
        $this->assertTrue(LucideIcon::exists('menu'));

        $svg = (string) LucideIcon::svg('search', ['class' => 'size-4', 'width' => '18', 'height' => '18']);

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('stroke-width="1.75"', $svg);
        $this->assertStringContainsString('aria-hidden="true"', $svg);
        $this->assertStringContainsString('class="size-4"', $svg);
    }

    public function test_unknown_or_unsafe_icon_names_are_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        LucideIcon::svg('../search');
    }

    public function test_the_public_icon_component_and_shell_use_lucide(): void
    {
        $icon = Blade::render('<x-public.icon name="search" :size="18" class="text-brand-royal" />');
        $alert = Blade::render('<x-public.alert title="Erreur" variant="danger">Corrigez le champ.</x-public.alert>');
        $external = Blade::render('<x-public.link href="https://example.com" external>Site</x-public.link>');

        $this->assertStringContainsString('lucide-search', $icon);
        $this->assertStringContainsString('circle-alert', $alert);
        $this->assertStringContainsString('external-link', $external);

        $this->get('/')
            ->assertOk()
            ->assertSee('lucide-search', false)
            ->assertSee('lucide-menu', false)
            ->assertSee('lucide-phone', false)
            ->assertDontSee('☎', false);
    }
}
