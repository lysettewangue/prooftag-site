<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Corporate\TranslatePage;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PageCode;
use App\Enums\StaffRole;
use App\Filament\Resources\SitePages\SitePageResource;
use App\Filament\Resources\TechnicalInspection\TechnicalInspectionResource;
use App\Models\Page;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitePagesAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_lists_the_ten_corporate_pages(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(SitePageResource::getUrl('index'))
            ->assertOk()
            ->assertSee('À propos')
            ->assertDontSee('technical_inspection');

        $this->assertSame(10, SitePageResource::getEloquentQuery()->count());
        $this->assertFalse(SitePageResource::canCreate());
    }

    public function test_communications_editor_can_open_about(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $about = Page::query()->where('code', PageCode::About)->firstOrFail();

        $this->actingAs($editor)
            ->get(SitePageResource::getUrl('edit', ['record' => $about]))
            ->assertOk();
    }

    public function test_technical_editor_cannot_open_corporate_pages(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $this->actingAs($editor)
            ->get(SitePageResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_translator_and_reviewer_can_open_pages(): void
    {
        $about = Page::query()->where('code', PageCode::About)->firstOrFail();

        $this->actingAs(User::factory()->staff(StaffRole::Translator)->create())
            ->get(SitePageResource::getUrl('edit', ['record' => $about]))
            ->assertOk();

        $this->actingAs(User::factory()->staff(StaffRole::ReviewerPublisher)->create())
            ->get(SitePageResource::getUrl('edit', ['record' => $about]))
            ->assertOk();
    }

    public function test_support_cannot_open_pages(): void
    {
        $this->actingAs(User::factory()->staff(StaffRole::SupportAgent)->create())
            ->get(SitePageResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_there_is_no_create_page_route(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get('/admin/pages/create')
            ->assertNotFound();
    }

    public function test_the_inspection_hub_is_not_in_this_module(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(SitePageResource::getUrl('edit', ['record' => TechnicalInspectionResource::hub()]))
            ->assertNotFound();
    }

    public function test_communications_editor_can_write_french_blocks(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $about = Page::query()->where('code', PageCode::About)->firstOrFail();

        (new TranslatePage)->handle($about, $editor, [
            'locale' => 'fr',
            'title' => 'À propos',
            'blocks' => [
                ['type' => 'rich_text', 'data' => ['content' => 'Présentation CATIS.']],
            ],
        ]);

        $this->assertSame(
            'Présentation CATIS.',
            $about->translationFor('fr')?->blocks[0]['content'] ?? null,
        );
    }

    public function test_communications_editor_cannot_write_english(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $about = Page::query()->where('code', PageCode::About)->firstOrFail();

        $this->expectException(AuthorizationException::class);

        (new TranslatePage)->handle($about, $editor, [
            'locale' => 'en',
            'title' => 'About',
            'blocks' => [],
        ]);
    }

    public function test_page_slugs_stay_frozen(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $about = Page::query()->where('code', PageCode::About)->firstOrFail();

        $this->expectException(DomainRuleViolation::class);

        (new TranslatePage)->handle($about, $admin, [
            'locale' => 'fr',
            'slug' => 'hacked',
            'title' => 'À propos',
        ]);
    }

    public function test_brand_keys_are_rejected_on_translate(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $about = Page::query()->where('code', PageCode::About)->firstOrFail();

        $this->expectException(DomainRuleViolation::class);

        (new TranslatePage)->handle($about, $admin, [
            'locale' => 'fr',
            'title' => 'À propos',
            'blocks' => [
                ['type' => 'statistics', 'items' => [['label' => 'Centres', 'value' => '0', 'color' => 'red']]],
            ],
        ]);
    }
}
