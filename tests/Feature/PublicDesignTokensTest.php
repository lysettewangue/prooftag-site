<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PublicDesignTokensTest extends TestCase
{
    public function test_the_approved_public_design_tokens_are_configured(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertIsString($css);

        foreach ([
            '--color-brand-royal: #0e6697',
            '--color-brand-azure-700: #007c9e',
            '--color-brand-azure-600: #087ea4',
            '--color-brand-cyan-500: #28d7df',
            '--color-brand-cyan-100: #d9fbff',
            '--color-surface-canvas: #f7fcfd',
            '--color-surface-subtle: #e8f8fb',
            '--color-ink: #12222b',
            '--color-muted: #55646d',
            '--color-control-border: #6b818c',
            '--color-status-warning: #b5473c',
            '--font-sans: \'DM Sans\'',
            '--font-reading: \'Source Serif 4\'',
            '--breakpoint-medium: 40.0625rem',
            '--breakpoint-wide: 64.0625rem',
            '--breakpoint-nav: 80rem',
            '--container-site: 80rem',
            '--spacing-target: 2.75rem',
            '--radius-card: 1.375rem',
            '--shadow-card:',
            '--motion-fast: 120ms',
        ] as $token) {
            $this->assertStringContainsString($token, $css);
        }
    }

    public function test_the_public_theme_does_not_define_a_yellow_brand_token(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        $this->assertIsString($css);
        $this->assertStringNotContainsString('--color-brand-yellow', $css);
        $this->assertStringNotContainsString('--color-status-warning: #ffc', strtolower($css));
        $this->assertStringNotContainsString('--color-status-warning: #fac', strtolower($css));
        $this->assertStringNotContainsString('--color-status-warning: #eab', strtolower($css));
    }
}
