<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Corporate\TranslateSiteSettings;
use App\Actions\Corporate\UpdateSiteSettings;
use App\Enums\StaffRole;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Models\SiteSetting;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_can_open_the_settings_form(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(SiteSettingResource::getUrl('edit', ['record' => 1]))
            ->assertOk();
    }

    public function test_support_cannot_open_settings(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(SiteSettingResource::getUrl('edit', ['record' => 1]))
            ->assertForbidden();
    }

    public function test_translator_can_open_settings_to_translate(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(SiteSettingResource::getUrl('edit', ['record' => 1]))
            ->assertOk();
    }

    public function test_filament_cannot_create_or_delete_the_settings_row(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $this->actingAs($admin);

        $this->assertFalse(SiteSettingResource::canCreate());
        $this->assertFalse(SiteSettingResource::canDelete(SiteSetting::query()->findOrFail(1)));
    }

    public function test_update_ignores_frozen_identity_fields(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $settings = SiteSetting::query()->findOrFail(1);
        $original = $settings->legal_name;

        (new UpdateSiteSettings)->handle($settings, $admin, [
            'legal_name' => 'HACKED SA',
            'email' => 'hacked@example.com',
            'social_linkedin' => 'https://www.linkedin.com/company/prooftag-catis',
        ]);

        $settings->refresh();
        $this->assertSame($original, $settings->legal_name);
        $this->assertSame('info@prooftagcatis.com', $settings->email);
        $this->assertSame('https://www.linkedin.com/company/prooftag-catis', $settings->social_linkedin);
    }

    public function test_translator_can_save_a_tagline_without_changing_identity(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $settings = SiteSetting::query()->findOrFail(1);

        (new TranslateSiteSettings)->handle($settings, $translator, [
            'locale' => 'fr',
            'tagline' => 'Services d’information technologique du Cameroun',
        ]);

        $this->assertSame(
            'Services d’information technologique du Cameroun',
            $settings->translationFor('fr')?->tagline,
        );
        $this->assertSame('PROOFTAG-CATIS S.A.', $settings->fresh()->legal_name);
    }
}
