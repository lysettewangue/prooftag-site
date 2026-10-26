<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\BrandPalette;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Tests\TestCase;

class AdminThemeTest extends TestCase
{
    public function test_the_admin_panel_uses_azure_without_amber(): void
    {
        $colors = Filament::getPanel('admin')->getColors();

        $this->assertSame(BrandPalette::filamentPrimary(), $colors['primary']);
        $this->assertNotSame(Color::Amber, $colors['primary']);
        $this->assertSame(BrandPalette::filamentWarning(), $colors['warning']);
        $this->assertNotSame(Color::Amber, $colors['warning']);
        $this->assertSame(Color::Slate, $colors['gray']);
    }

    public function test_the_login_page_loads_the_official_logo(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('images/brand/logo.png', false)
            ->assertSee('Plus Jakarta Sans', false)
            ->assertSee('Plateforme corporate', false)
            ->assertSee('Accès réservé aux équipes CATIS', false);
    }
}
