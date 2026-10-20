<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionFoundationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function test_the_eight_roles_and_custom_permissions_are_seeded(): void
    {
        $this->assertEqualsCanonicalizing(StaffRole::codes(), Role::query()->pluck('name')->all());
        $this->assertEquals(8, Role::query()->count());

        foreach (StaffPermission::codes() as $permission) {
            $this->assertTrue(Permission::query()->where('name', $permission)->exists(), $permission);
        }
    }

    public function test_unauthenticated_admin_redirects_to_login(): void
    {
        $this->get('/admin')->assertRedirect();
        $this->get('/admin/login')->assertOk();
        $this->get('/admin/register')->assertNotFound();
    }

    public function test_a_user_without_a_staff_role_cannot_access_the_panel(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->canAccessPanel(Filament::getPanel('admin')));

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_each_staff_role_can_access_the_panel(): void
    {
        foreach (StaffRole::cases() as $role) {
            $user = User::factory()->staff($role)->create();

            $this->assertTrue($user->canAccessPanel(Filament::getPanel('admin')), $role->value);
            $this->assertEquals([$role->value], $user->getRoleNames()->all());

            $this->actingAs($user)
                ->get('/admin')
                ->assertOk();
        }
    }

    public function test_assigning_a_staff_role_replaces_the_previous_one(): void
    {
        $user = User::factory()->staff(StaffRole::Translator)->create();
        $user->assignStaffRole(StaffRole::SupportAgent);

        $this->assertTrue($user->hasRole(StaffRole::SupportAgent->value));
        $this->assertFalse($user->hasRole(StaffRole::Translator->value));
        $this->assertCount(1, $user->roles);
    }

    public function test_the_ability_matrix_matches_the_role_register(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $publisher = User::factory()->staff(StaffRole::ReviewerPublisher)->create();
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->assertTrue($translator->can('translate'));
        $this->assertFalse($translator->can('publish'));
        $this->assertFalse($translator->can('contact_inbox'));

        $this->assertTrue($publisher->can('publish'));
        $this->assertFalse($publisher->can('translate'));

        $this->assertTrue($support->can('contact_inbox'));
        $this->assertFalse($support->can('publish'));
        $this->assertFalse($support->can('manage_users'));

        $this->assertTrue($admin->can('manage_users'));
        $this->assertTrue($admin->can('publish'));
        $this->assertFalse($admin->can('ViewAny:Role'));
    }

    public function test_only_super_admin_can_open_the_role_resource(): void
    {
        $superAdmin = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->assertTrue($superAdmin->can('ViewAny:Role'));

        $this->actingAs($superAdmin)
            ->get('/admin/shield/roles')
            ->assertOk();

        $this->actingAs($admin)
            ->get('/admin/shield/roles')
            ->assertForbidden();

        $this->actingAs($translator)
            ->get('/admin/shield/roles')
            ->assertForbidden();
    }
}
