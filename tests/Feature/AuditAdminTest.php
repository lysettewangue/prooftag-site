<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\StaffRole;
use App\Filament\Resources\ActivityLog\ActivityLogResource;
use App\Models\Activity;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_can_list_the_audit_log(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(ActivityLogResource::getUrl('index'))
            ->assertOk();

        $this->assertFalse(ActivityLogResource::canCreate());
        $this->assertFalse(ActivityLogResource::canDeleteAny());
    }

    public function test_support_cannot_open_audit(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(ActivityLogResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_translator_cannot_open_audit(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(ActivityLogResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_communications_editor_cannot_open_audit(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(ActivityLogResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_super_admin_cannot_create_or_delete_audit_rows(): void
    {
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $this->actingAs($super);

        $this->assertFalse(ActivityLogResource::canCreate());

        $activity = Activity::query()->latest('id')->first();

        if ($activity instanceof Activity) {
            $this->assertFalse(ActivityLogResource::canDelete($activity));
            $this->assertFalse(ActivityLogResource::canEdit($activity));
        }
    }

    public function test_admin_can_view_an_activity_without_passwords(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create([
            'email' => 'audit-view@prooftag-catis.test',
        ]);

        $activity = Activity::query()
            ->where('subject_type', $admin->getMorphClass())
            ->where('subject_id', $admin->id)
            ->where('event', 'created')
            ->firstOrFail();

        $encoded = json_encode($activity->properties) ?: '';

        $this->assertStringNotContainsString('password', $encoded);
        $this->assertStringNotContainsString('$2y$', $encoded);

        $this->actingAs($admin)
            ->get(ActivityLogResource::getUrl('view', ['record' => $activity]))
            ->assertOk();
    }
}
