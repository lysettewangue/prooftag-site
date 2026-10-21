<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Expertise\TranslateExpertise;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\ExpertiseCode;
use App\Enums\StaffRole;
use App\Filament\Resources\Expertises\ExpertiseResource;
use App\Models\Expertise;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpertisesAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_can_list_the_five_expertises(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(ExpertiseResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Recherche et développement')
            ->assertSee('Support technique')
            ->assertSee('Sécurité documentaire');

        $this->assertSame(5, Expertise::query()->count());
        $this->assertFalse(ExpertiseResource::canCreate());
    }

    public function test_technical_editor_can_open_an_expertise(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $expertise = Expertise::query()->where('code', ExpertiseCode::ResearchDevelopment)->firstOrFail();

        $this->actingAs($editor)
            ->get(ExpertiseResource::getUrl('edit', ['record' => $expertise]))
            ->assertOk();
    }

    public function test_translator_can_open_an_expertise_to_translate(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $expertise = Expertise::query()->firstOrFail();

        $this->actingAs($translator)
            ->get(ExpertiseResource::getUrl('edit', ['record' => $expertise]))
            ->assertOk();
    }

    public function test_reviewer_can_open_expertises_to_publish(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(ExpertiseResource::getUrl('index'))
            ->assertOk();
    }

    public function test_communications_editor_cannot_open_expertises(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(ExpertiseResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_support_cannot_open_expertises(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(ExpertiseResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_there_is_no_create_expertise_route(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get('/admin/expertises/create')
            ->assertNotFound();
    }

    public function test_expertise_title_must_not_copy_a_product_name(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $expertise = Expertise::query()->where('code', ExpertiseCode::ResearchDevelopment)->firstOrFail();

        $this->expectException(DomainRuleViolation::class);

        (new TranslateExpertise)->handle($expertise, $admin, [
            'locale' => 'fr',
            'slug' => $expertise->code->slugs()['fr'],
            'title' => 'CERTIDOCS CT',
        ]);
    }
}
