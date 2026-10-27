<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\SiteSetting;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicFooterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SiteSettingSeeder::class);
    }

    public function test_the_french_footer_uses_the_global_settings_record(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('PROOFTAG-CATIS S.A.')
            ->assertSee("Immeuble Résidence Le Carat, Hôtel de l'Air, Bonapriso")
            ->assertSee('BP 4601 Douala, Cameroun')
            ->assertSee('href="tel:+237699192919"', false)
            ->assertSee('href="mailto:info@prooftagcatis.com"', false)
            ->assertSee('Mentions légales')
            ->assertSee('Politique de confidentialité')
            ->assertDontSee('data-footer-social="true"', false);
    }

    public function test_the_english_footer_localises_the_frozen_display_lines(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('Cameroon Technology Information Services')
            ->assertSee('P.O. Box 4601 Douala, Cameroon')
            ->assertSee('Tel.: +237 699 19 29 19')
            ->assertSee('Legal notice')
            ->assertSee('Privacy policy')
            ->assertSee('All rights reserved.');
    }

    public function test_only_verified_non_empty_social_urls_are_rendered(): void
    {
        SiteSetting::query()->findOrFail(1)->forceFill([
            'social_linkedin' => 'https://www.linkedin.com/company/prooftag-catis',
            'social_facebook' => null,
            'social_x' => 'not-a-url',
        ])->save();

        $this->get('/')
            ->assertOk()
            ->assertSee('data-footer-social="true"', false)
            ->assertSee('https://www.linkedin.com/company/prooftag-catis', false)
            ->assertSee('LinkedIn')
            ->assertDontSee('Facebook')
            ->assertDontSee('not-a-url', false);
    }

    public function test_the_shell_still_renders_when_settings_have_not_been_seeded(): void
    {
        SiteSetting::query()->delete();

        $this->get('/')
            ->assertOk()
            ->assertSee('data-public-shell-region="footer"', false)
            ->assertSee(config('app.name'))
            ->assertDontSee('href="mailto:', false);
    }
}
