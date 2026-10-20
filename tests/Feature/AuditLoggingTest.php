<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\StaffRole;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Filament\Auth\Pages\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class AuditLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function test_seeding_roles_does_not_write_activity(): void
    {
        $this->assertSame(0, Activity::query()->count());
    }

    public function test_creating_and_updating_a_user_is_logged_without_secrets(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'staff@example.test',
        ]);

        $created = Activity::query()
            ->where('subject_type', $user->getMorphClass())
            ->where('subject_id', $user->id)
            ->where('event', 'created')
            ->first();

        $this->assertNotNull($created);
        $this->assertSame('staff', $created->log_name);
        $this->assertStringNotContainsString('password', json_encode($created->attribute_changes) ?: '');
        $this->assertStringNotContainsString('$2y$', json_encode($created->attribute_changes) ?: '');

        $user->update(['name' => 'Updated Name']);

        $updated = Activity::query()
            ->where('subject_type', $user->getMorphClass())
            ->where('subject_id', $user->id)
            ->where('event', 'updated')
            ->latest('id')
            ->first();

        $this->assertNotNull($updated);
        $this->assertSame('Updated Name', data_get($updated->attribute_changes, 'attributes.name'));
        $this->assertNull(data_get($updated->attribute_changes, 'attributes.password'));
    }

    public function test_assigning_a_staff_role_is_logged(): void
    {
        $actor = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $target = User::factory()->create();

        $this->actingAs($actor);
        $target->assignStaffRole(StaffRole::Translator);

        $this->assertTrue(
            Activity::query()
                ->where('log_name', 'staff')
                ->where('event', 'role_attached')
                ->where('subject_id', $target->id)
                ->where('causer_id', $actor->id)
                ->exists(),
        );
    }

    public function test_creating_a_role_is_logged(): void
    {
        $role = Role::create([
            'name' => 'temporary_audit_role',
            'guard_name' => 'web',
        ]);

        $this->assertTrue(
            Activity::query()
                ->where('log_name', 'staff')
                ->where('event', 'created')
                ->where('subject_type', $role->getMorphClass())
                ->where('subject_id', $role->id)
                ->exists(),
        );
    }

    public function test_admin_login_and_failed_login_are_logged(): void
    {
        $user = User::factory()->staff(StaffRole::Translator)->create([
            'email' => 'auditor@example.test',
        ]);

        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'nobody@example.test',
                'password' => 'wrong-password',
            ])
            ->call('authenticate');

        $failed = Activity::query()
            ->where('log_name', 'auth')
            ->where('event', 'login_failed')
            ->latest('id')
            ->first();

        $this->assertNotNull($failed);
        $this->assertSame('nobody@example.test', data_get($failed->properties, 'credentials.email'));
        $this->assertNull(data_get($failed->properties, 'credentials.password'));

        Livewire::test(Login::class)
            ->fillForm([
                'email' => $user->email,
                'password' => 'password',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertTrue(
            Activity::query()
                ->where('log_name', 'auth')
                ->where('event', 'login')
                ->where('causer_id', $user->id)
                ->exists(),
        );
    }

    public function test_the_public_home_does_not_write_activity(): void
    {
        $this->get('/')->assertOk();

        $this->assertSame(0, Activity::query()->count());
    }
}
