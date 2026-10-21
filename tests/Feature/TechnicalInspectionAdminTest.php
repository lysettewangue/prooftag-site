<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Corporate\TranslatePage;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PageCode;
use App\Enums\StaffRole;
use App\Filament\Resources\TechnicalInspection\TechnicalInspectionResource;
use App\Models\Page;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TechnicalInspectionAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_can_open_the_inspection_hub(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $page = TechnicalInspectionResource::hub();

        $this->actingAs($admin)
            ->get(TechnicalInspectionResource::getUrl('edit', ['record' => $page]))
            ->assertOk()
            ->assertSee('Contrôle technique')
            ->assertSee('controle-technique');

        $this->assertFalse(TechnicalInspectionResource::canCreate());
        $this->assertSame(PageCode::TechnicalInspection, $page->code);
    }

    public function test_technical_editor_can_open_the_inspection_hub(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $this->actingAs($editor)
            ->get(TechnicalInspectionResource::getUrl('edit', ['record' => TechnicalInspectionResource::hub()]))
            ->assertOk();
    }

    public function test_translator_can_open_the_inspection_hub_to_translate(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(TechnicalInspectionResource::getUrl('edit', ['record' => TechnicalInspectionResource::hub()]))
            ->assertOk();
    }

    public function test_reviewer_can_open_the_inspection_hub_to_publish(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(TechnicalInspectionResource::getUrl('edit', ['record' => TechnicalInspectionResource::hub()]))
            ->assertOk();
    }

    public function test_communications_editor_cannot_open_the_inspection_hub(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(TechnicalInspectionResource::getUrl('edit', ['record' => TechnicalInspectionResource::hub()]))
            ->assertForbidden();
    }

    public function test_support_cannot_open_the_inspection_hub(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(TechnicalInspectionResource::getUrl('edit', ['record' => TechnicalInspectionResource::hub()]))
            ->assertForbidden();
    }

    public function test_there_is_no_create_inspection_route(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get('/admin/technical-inspection/overview/create')
            ->assertNotFound();
    }

    public function test_other_pages_are_not_in_this_module(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $about = Page::query()->where('code', PageCode::About)->firstOrFail();

        $this->actingAs($admin)
            ->get(TechnicalInspectionResource::getUrl('edit', ['record' => $about]))
            ->assertNotFound();
    }

    public function test_technical_editor_can_update_the_french_overview(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $page = TechnicalInspectionResource::hub();

        (new TranslatePage)->handle($page, $editor, [
            'locale' => 'fr',
            'slug' => 'controle-technique',
            'title' => 'Contrôle technique',
            'overview' => 'Aperçu du hub contrôle technique.',
        ]);

        $this->assertSame(
            'Aperçu du hub contrôle technique.',
            $page->translationFor('fr')?->blocks[0]['content'] ?? null,
        );
    }

    public function test_technical_editor_cannot_write_english(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $page = TechnicalInspectionResource::hub();

        $this->expectException(AuthorizationException::class);

        (new TranslatePage)->handle($page, $editor, [
            'locale' => 'en',
            'slug' => 'technical-inspection',
            'title' => 'Technical inspection',
            'overview' => 'Hub overview.',
        ]);
    }

    public function test_inspection_slugs_stay_frozen(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $page = TechnicalInspectionResource::hub();

        $this->expectException(DomainRuleViolation::class);

        (new TranslatePage)->handle($page, $admin, [
            'locale' => 'fr',
            'slug' => 'hacked',
            'title' => 'Contrôle technique',
        ]);
    }
}
