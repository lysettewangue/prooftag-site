<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Corporate\CreateTeamMember;
use App\Actions\Corporate\TranslateTeamMember;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PublicationStatus;
use App\Enums\StaffRole;
use App\Filament\Resources\TeamMembers\TeamMemberResource;
use App\Models\TeamMember;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_empty_team_list_is_valid(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(TeamMemberResource::getUrl('index'))
            ->assertOk();

        $this->assertSame(0, TeamMember::query()->count());
        $this->assertTrue(TeamMemberResource::canCreate());
        $this->assertFalse(TeamMemberResource::canDeleteAny());
    }

    public function test_communications_editor_can_open_create(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(TeamMemberResource::getUrl('create'))
            ->assertOk();
    }

    public function test_translator_can_list_but_cannot_create(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(TeamMemberResource::getUrl('index'))
            ->assertOk();

        $this->assertFalse(TeamMemberResource::canCreate());

        $this->actingAs($translator)
            ->get(TeamMemberResource::getUrl('create'))
            ->assertForbidden();
    }

    public function test_reviewer_can_open_team(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(TeamMemberResource::getUrl('index'))
            ->assertOk();
    }

    public function test_technical_editor_cannot_open_team(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $this->actingAs($editor)
            ->get(TeamMemberResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_support_cannot_open_team(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(TeamMemberResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_communications_editor_can_create_french_draft(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $member = $this->makeMember($editor);

        $this->assertSame(PublicationStatus::Draft, $member->publication_status);
        $this->assertSame('TCHANGA Jean', $member->full_name);
        $this->assertSame('Directeur général', $member->translationFor('fr')?->title);
        $this->assertSame('directeur-general', $member->translationFor('fr')?->slug);
    }

    public function test_communications_editor_cannot_write_english(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $member = $this->makeMember($editor);

        $this->expectException(AuthorizationException::class);

        (new TranslateTeamMember)->handle($member, $editor, [
            'locale' => 'en',
            'title' => 'Managing Director',
        ]);
    }

    public function test_translator_can_write_english_and_open_edit(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $member = $this->makeMember($editor);

        (new TranslateTeamMember)->handle($member, $translator, [
            'locale' => 'en',
            'title' => 'Managing Director',
        ]);

        $this->assertSame('Managing Director', $member->translationFor('en')?->title);

        $this->actingAs($translator)
            ->get(TeamMemberResource::getUrl('edit', ['record' => $member]))
            ->assertOk();
    }

    public function test_team_member_cannot_be_hard_deleted(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $member = $this->makeMember($admin);

        $this->assertFalse($admin->can('delete', $member));
        $this->assertFalse(TeamMemberResource::canDelete($member));

        $this->actingAs($super);
        $this->assertFalse(TeamMemberResource::canDelete($member));
        $this->assertFalse(TeamMemberResource::canForceDelete($member));
    }

    public function test_team_member_slugs_must_be_unique_per_locale(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $this->makeMember($editor);
        $second = (new CreateTeamMember)->handle($editor, [
            'full_name' => 'NGAE Paul',
            'sort_order' => 2,
        ]);

        $this->expectException(DomainRuleViolation::class);

        (new TranslateTeamMember)->handle($second, $editor, [
            'locale' => 'fr',
            'title' => 'Autre fonction',
            'slug' => 'directeur-general',
        ]);
    }

    private function makeMember(User $actor, string $name = 'TCHANGA Jean'): TeamMember
    {
        $member = (new CreateTeamMember)->handle($actor, [
            'full_name' => $name,
            'sort_order' => 1,
        ]);

        (new TranslateTeamMember)->handle($member, $actor, [
            'locale' => 'fr',
            'title' => 'Directeur général',
        ]);

        return $member;
    }
}
