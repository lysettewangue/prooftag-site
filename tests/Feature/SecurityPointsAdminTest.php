<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Inspection\ReorderSecurityPoints;
use App\Actions\Inspection\TranslateSecurityPoint;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\StaffRole;
use App\Filament\Resources\SecurityPoints\SecurityPointResource;
use App\Models\SecurityPoint;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityPointsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_admin_can_list_the_twelve_security_points(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(SecurityPointResource::getUrl('index'))
            ->assertOk()
            ->assertSee('Prise de photo')
            ->assertSee('Localisation par GPS');

        $this->assertSame(12, SecurityPoint::query()->count());
        $this->assertFalse(SecurityPointResource::canCreate());
    }

    public function test_technical_editor_can_open_a_security_point(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $point = SecurityPoint::query()->where('sort_order', 1)->firstOrFail();

        $this->actingAs($editor)
            ->get(SecurityPointResource::getUrl('edit', ['record' => $point]))
            ->assertOk();
    }

    public function test_translator_can_open_a_security_point_to_translate(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $point = SecurityPoint::query()->firstOrFail();

        $this->actingAs($translator)
            ->get(SecurityPointResource::getUrl('edit', ['record' => $point]))
            ->assertOk();
    }

    public function test_reviewer_can_open_security_points_to_publish(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(SecurityPointResource::getUrl('index'))
            ->assertOk();
    }

    public function test_communications_editor_cannot_open_security_points(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(SecurityPointResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_support_cannot_open_security_points(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(SecurityPointResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_there_is_no_create_security_point_route(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get('/admin/technical-inspection/security-points/create')
            ->assertNotFound();
    }

    public function test_technical_editor_can_update_french_copy(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $point = SecurityPoint::query()->where('sort_order', 4)->firstOrFail();

        (new TranslateSecurityPoint)->handle($point, $editor, [
            'locale' => 'fr',
            'title' => 'Prise de photographie',
            'body' => 'Explication du point 4.',
        ]);

        $this->assertSame('Prise de photographie', $point->translationFor('fr')?->title);
    }

    public function test_technical_editor_cannot_write_english(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $point = SecurityPoint::query()->firstOrFail();

        $this->expectException(AuthorizationException::class);

        (new TranslateSecurityPoint)->handle($point, $editor, [
            'locale' => 'en',
            'title' => 'Photograph capture',
            'body' => 'Point 4 explanation.',
        ]);
    }

    public function test_technical_editor_can_reorder_the_twelve_points(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $ids = SecurityPoint::query()->orderBy('sort_order')->pluck('id')->all();
        $reversed = array_reverse($ids);

        (new ReorderSecurityPoints)->handle($editor, $reversed);

        $this->assertSame(
            $reversed,
            SecurityPoint::query()->orderBy('sort_order')->pluck('id')->all(),
        );
        $this->assertSame(range(1, 12), SecurityPoint::query()->orderBy('sort_order')->pluck('sort_order')->all());
    }

    public function test_reorder_rejects_a_partial_list(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $ids = SecurityPoint::query()->orderBy('sort_order')->pluck('id')->take(11)->all();

        $this->expectException(DomainRuleViolation::class);

        (new ReorderSecurityPoints)->handle($admin, $ids);
    }

    public function test_translator_cannot_reorder(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $ids = SecurityPoint::query()->orderBy('sort_order')->pluck('id')->all();

        $this->expectException(AuthorizationException::class);

        (new ReorderSecurityPoints)->handle($translator, $ids);
    }
}
