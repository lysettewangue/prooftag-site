<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Staff\CreateStaffUser;
use App\Actions\Staff\DeleteStaffUser;
use App\Actions\Staff\UpdateStaffUser;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\StaffRole;
use App\Filament\Resources\StaffUsers\StaffUserResource;
use App\Models\User;
use App\Support\StaffUsers;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffUsersAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(StaffUserResource::getUrl('index'))
            ->assertOk();

        $this->assertTrue(StaffUserResource::canCreate());
    }

    public function test_support_cannot_open_users(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(StaffUserResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_translator_cannot_open_users(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(StaffUserResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_a_support_user(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $created = (new CreateStaffUser)->handle($admin, [
            'name' => 'Agent Test',
            'email' => 'agent@prooftag-catis.test',
            'password' => 'password1',
            'role' => StaffRole::SupportAgent->value,
        ]);

        $this->assertTrue($created->hasRole(StaffRole::SupportAgent->value));
        $this->assertCount(1, $created->roles);

        $this->actingAs($admin)
            ->get(StaffUserResource::getUrl('edit', ['record' => $created]))
            ->assertOk();
    }

    public function test_admin_cannot_create_a_super_admin(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->expectException(DomainRuleViolation::class);

        (new CreateStaffUser)->handle($admin, [
            'name' => 'Break glass',
            'email' => 'break@prooftag-catis.test',
            'password' => 'password1',
            'role' => StaffRole::SuperAdmin->value,
        ]);
    }

    public function test_admin_cannot_update_a_super_admin(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();

        $this->expectException(AuthorizationException::class);

        (new UpdateStaffUser)->handle($super, $admin, [
            'name' => 'Hacked',
        ]);
    }

    public function test_super_admin_can_create_an_admin(): void
    {
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();

        $created = (new CreateStaffUser)->handle($super, [
            'name' => 'Ops',
            'email' => 'ops@prooftag-catis.test',
            'password' => 'password1',
            'role' => StaffRole::Admin->value,
        ]);

        $this->assertTrue($created->hasRole(StaffRole::Admin->value));
    }

    public function test_last_super_admin_cannot_be_demoted(): void
    {
        $only = User::factory()->staff(StaffRole::SuperAdmin)->create();

        $this->assertTrue(StaffUsers::isLastSuperAdmin($only));

        $this->expectException(DomainRuleViolation::class);

        (new UpdateStaffUser)->handle($only, $only, [
            'role' => StaffRole::Admin->value,
        ]);
    }

    public function test_last_super_admin_cannot_be_deleted(): void
    {
        $only = User::factory()->staff(StaffRole::SuperAdmin)->create();

        $this->actingAs($only);
        $this->assertFalse(StaffUserResource::canDelete($only));

        $this->expectException(DomainRuleViolation::class);
        (new DeleteStaffUser)->handle($only, $only);
    }

    public function test_duplicate_email_is_rejected(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        User::factory()->staff(StaffRole::Translator)->create([
            'email' => 'taken@prooftag-catis.test',
        ]);

        $this->expectException(DomainRuleViolation::class);

        (new CreateStaffUser)->handle($admin, [
            'name' => 'Other',
            'email' => 'taken@prooftag-catis.test',
            'password' => 'password1',
            'role' => StaffRole::Translator->value,
        ]);
    }

    public function test_assigning_a_role_replaces_the_previous_one(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $staff = (new CreateStaffUser)->handle($admin, [
            'name' => 'Switcher',
            'email' => 'switch@prooftag-catis.test',
            'password' => 'password1',
            'role' => StaffRole::Translator->value,
        ]);

        (new UpdateStaffUser)->handle($staff, $admin, [
            'role' => StaffRole::SupportAgent->value,
        ]);

        $staff->refresh();
        $this->assertTrue($staff->hasRole(StaffRole::SupportAgent->value));
        $this->assertFalse($staff->hasRole(StaffRole::Translator->value));
        $this->assertCount(1, $staff->roles);
    }
}
