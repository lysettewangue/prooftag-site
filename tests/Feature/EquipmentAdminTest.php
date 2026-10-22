<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Inspection\CreateEquipment;
use App\Actions\Inspection\TranslateEquipment;
use App\Actions\Inspection\UpdateEquipment;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\EquipmentLifecycle;
use App\Enums\StaffRole;
use App\Filament\Resources\Equipment\EquipmentResource;
use App\Models\Equipment;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_empty_equipment_list_is_valid(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(EquipmentResource::getUrl('index'))
            ->assertOk();

        $this->assertSame(0, Equipment::query()->count());
        $this->assertTrue(EquipmentResource::canCreate());
        $this->assertFalse(EquipmentResource::canDeleteAny());
    }

    public function test_technical_editor_can_open_create(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $this->actingAs($editor)
            ->get(EquipmentResource::getUrl('create'))
            ->assertOk();
    }

    public function test_translator_can_list_but_cannot_create(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(EquipmentResource::getUrl('index'))
            ->assertOk();

        $this->assertFalse(EquipmentResource::canCreate());

        $this->actingAs($translator)
            ->get(EquipmentResource::getUrl('create'))
            ->assertForbidden();
    }

    public function test_reviewer_can_open_equipment(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(EquipmentResource::getUrl('index'))
            ->assertOk();
    }

    public function test_communications_editor_cannot_open_equipment(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(EquipmentResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_support_cannot_open_equipment(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(EquipmentResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_technical_editor_can_create_current_equipment_in_french(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $equipment = (new CreateEquipment)->handle($editor, [
            'lifecycle' => EquipmentLifecycle::Current->value,
            'sort_order' => 1,
        ]);

        (new TranslateEquipment)->handle($equipment, $editor, [
            'locale' => 'fr',
            'title' => 'Banc de visio',
        ]);

        $this->assertSame(EquipmentLifecycle::Current, $equipment->fresh()?->lifecycle);
        $this->assertSame('Banc de visio', $equipment->translationFor('fr')?->title);
        $this->assertSame('banc-de-visio', $equipment->translationFor('fr')?->slug);
    }

    public function test_technical_editor_cannot_write_english(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $equipment = $this->makeEquipment($editor);

        $this->expectException(AuthorizationException::class);

        (new TranslateEquipment)->handle($equipment, $editor, [
            'locale' => 'en',
            'title' => 'Vision bench',
        ]);
    }

    public function test_translator_can_write_english_and_open_edit(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $equipment = $this->makeEquipment($editor);

        (new TranslateEquipment)->handle($equipment, $translator, [
            'locale' => 'en',
            'title' => 'Vision bench',
        ]);

        $this->assertSame('Vision bench', $equipment->translationFor('en')?->title);
        $this->assertSame('vision-bench', $equipment->translationFor('en')?->slug);

        $this->actingAs($translator)
            ->get(EquipmentResource::getUrl('edit', ['record' => $equipment]))
            ->assertOk();
    }

    public function test_technical_editor_can_mark_equipment_legacy(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $equipment = $this->makeEquipment($editor);

        (new UpdateEquipment)->handle($equipment, $editor, [
            'lifecycle' => EquipmentLifecycle::Legacy->value,
        ]);

        $this->assertSame(EquipmentLifecycle::Legacy, $equipment->fresh()?->lifecycle);
    }

    public function test_equipment_cannot_be_hard_deleted(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $equipment = $this->makeEquipment($admin);

        $this->assertFalse($admin->can('delete', $equipment));
        $this->assertFalse(EquipmentResource::canDelete($equipment));
        $this->assertFalse(EquipmentResource::canForceDelete($equipment));
        $this->assertFalse(EquipmentResource::canForceDeleteAny());

        $this->actingAs($super);
        $this->assertFalse(EquipmentResource::canDelete($equipment));
        $this->assertFalse(EquipmentResource::canForceDelete($equipment));
    }

    public function test_equipment_slugs_must_be_unique_per_locale(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $this->makeEquipment($editor, 'Banc de visio');
        $second = (new CreateEquipment)->handle($editor, [
            'lifecycle' => EquipmentLifecycle::Current->value,
            'sort_order' => 2,
        ]);

        $this->expectException(DomainRuleViolation::class);

        (new TranslateEquipment)->handle($second, $editor, [
            'locale' => 'fr',
            'title' => 'Autre banc',
            'slug' => 'banc-de-visio',
        ]);
    }

    private function makeEquipment(User $actor, string $title = 'Banc de visio'): Equipment
    {
        $equipment = (new CreateEquipment)->handle($actor, [
            'lifecycle' => EquipmentLifecycle::Current->value,
            'sort_order' => 1,
        ]);

        (new TranslateEquipment)->handle($equipment, $actor, [
            'locale' => 'fr',
            'title' => $title,
        ]);

        return $equipment;
    }
}
