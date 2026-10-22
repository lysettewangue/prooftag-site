<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Seo\UpsertRedirect;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\StaffRole;
use App\Filament\Resources\Redirects\RedirectResource;
use App\Models\Redirect;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_empty_redirect_list_is_valid(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(RedirectResource::getUrl('index'))
            ->assertOk();

        $this->assertSame(0, Redirect::query()->count());
        $this->assertTrue(RedirectResource::canCreate());
        $this->assertFalse(RedirectResource::canDeleteAny());
    }

    public function test_admin_can_open_create(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(RedirectResource::getUrl('create'))
            ->assertOk();
    }

    public function test_support_cannot_open_redirects(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(RedirectResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_translator_cannot_open_redirects(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(RedirectResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_communications_editor_cannot_open_redirects(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(RedirectResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_technical_editor_cannot_open_redirects(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $this->actingAs($editor)
            ->get(RedirectResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_a_redirect(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $redirect = (new UpsertRedirect)->handle($admin, [
            'from_host' => 'site.prooftagcatis.com',
            'from_path' => '/old',
            'to_path' => '/produits',
            'status_code' => 301,
        ]);

        $this->assertTrue($redirect->is_active);
        $this->assertSame(301, $redirect->status_code);

        $this->actingAs($admin)
            ->get(RedirectResource::getUrl('edit', ['record' => $redirect]))
            ->assertOk();
    }

    public function test_duplicate_host_and_path_is_rejected(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        (new UpsertRedirect)->handle($admin, [
            'from_host' => 'www.prooftagcatis.com',
            'from_path' => '/ancien',
            'to_path' => '/a-propos',
        ]);

        $this->expectException(DomainRuleViolation::class);

        (new UpsertRedirect)->handle($admin, [
            'from_host' => 'www.prooftagcatis.com',
            'from_path' => '/ancien',
            'to_path' => '/contact',
        ]);
    }

    public function test_unknown_host_is_rejected(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->expectException(DomainRuleViolation::class);

        (new UpsertRedirect)->handle($admin, [
            'from_host' => 'evil.example',
            'from_path' => '/x',
            'to_path' => '/y',
        ]);
    }

    public function test_redirect_cannot_be_hard_deleted(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();

        $redirect = (new UpsertRedirect)->handle($admin, [
            'from_host' => 'prooftagcatis.com',
            'from_path' => '/legacy',
            'to_path' => '/',
            'is_active' => false,
        ]);

        $this->assertFalse($admin->can('delete', $redirect));
        $this->actingAs($super);
        $this->assertFalse(RedirectResource::canDelete($redirect));
    }

    public function test_support_cannot_create_a_redirect(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->expectException(AuthorizationException::class);

        (new UpsertRedirect)->handle($support, [
            'from_host' => 'www.prooftagcatis.com',
            'from_path' => '/a',
            'to_path' => '/b',
        ]);
    }
}
