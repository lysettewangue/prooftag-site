<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Corporate\CreateCareer;
use App\Actions\Corporate\TranslateCareer;
use App\Actions\Corporate\UpdateCareer;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\CareerKind;
use App\Enums\PublicationStatus;
use App\Enums\StaffRole;
use App\Filament\Resources\Careers\CareerResource;
use App\Models\Career;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareersAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_empty_career_list_is_valid(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(CareerResource::getUrl('index'))
            ->assertOk();

        $this->assertSame(0, Career::query()->count());
        $this->assertTrue(CareerResource::canCreate());
        $this->assertFalse(CareerResource::canDeleteAny());
    }

    public function test_communications_editor_can_open_create(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(CareerResource::getUrl('create'))
            ->assertOk();
    }

    public function test_translator_can_list_but_cannot_create(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(CareerResource::getUrl('index'))
            ->assertOk();

        $this->assertFalse(CareerResource::canCreate());

        $this->actingAs($translator)
            ->get(CareerResource::getUrl('create'))
            ->assertForbidden();
    }

    public function test_reviewer_can_open_careers(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(CareerResource::getUrl('index'))
            ->assertOk();
    }

    public function test_technical_editor_cannot_open_careers(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $this->actingAs($editor)
            ->get(CareerResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_support_cannot_open_careers(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(CareerResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_communications_editor_can_create_french_draft(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $career = $this->makeCareer($editor);

        $this->assertSame(PublicationStatus::Draft, $career->publication_status);
        $this->assertSame(CareerKind::Job, $career->kind);
        $this->assertSame('Ingénieur CT', $career->translationFor('fr')?->title);
        $this->assertSame('ingenieur-ct', $career->translationFor('fr')?->slug);
        $this->assertNull($career->application_url);
    }

    public function test_communications_editor_cannot_write_english(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $career = $this->makeCareer($editor);

        $this->expectException(AuthorizationException::class);

        (new TranslateCareer)->handle($career, $editor, [
            'locale' => 'en',
            'title' => 'CT engineer',
        ]);
    }

    public function test_translator_can_write_english_and_open_edit(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $career = $this->makeCareer($editor);

        (new TranslateCareer)->handle($career, $translator, [
            'locale' => 'en',
            'title' => 'CT engineer',
        ]);

        $this->assertSame('CT engineer', $career->translationFor('en')?->title);
        $this->assertSame('ct-engineer', $career->translationFor('en')?->slug);

        $this->actingAs($translator)
            ->get(CareerResource::getUrl('edit', ['record' => $career]))
            ->assertOk();
    }

    public function test_career_cannot_be_hard_deleted(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $career = $this->makeCareer($admin);

        $this->assertFalse($admin->can('delete', $career));
        $this->assertFalse(CareerResource::canDelete($career));

        $this->actingAs($super);
        $this->assertFalse(CareerResource::canDelete($career));
        $this->assertFalse(CareerResource::canForceDelete($career));
    }

    public function test_career_slugs_must_be_unique_per_locale(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $this->makeCareer($editor);
        $second = (new CreateCareer)->handle($editor, [
            'kind' => CareerKind::Internship->value,
        ]);

        $this->expectException(DomainRuleViolation::class);

        (new TranslateCareer)->handle($second, $editor, [
            'locale' => 'fr',
            'title' => 'Autre offre',
            'slug' => 'ingenieur-ct',
        ]);
    }

    public function test_empty_application_url_is_stored_as_null(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $career = (new CreateCareer)->handle($editor, [
            'kind' => CareerKind::Job->value,
            'application_url' => '',
        ]);

        $this->assertNull($career->application_url);

        $updated = (new UpdateCareer)->handle($career, $editor, ['application_url' => '   ']);

        $this->assertNull($updated->application_url);
    }

    public function test_invalid_application_url_is_rejected(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->expectException(DomainRuleViolation::class);

        (new CreateCareer)->handle($editor, [
            'kind' => CareerKind::Job->value,
            'application_url' => 'not-a-url',
        ]);
    }

    private function makeCareer(User $actor): Career
    {
        $career = (new CreateCareer)->handle($actor, [
            'kind' => CareerKind::Job->value,
        ]);

        (new TranslateCareer)->handle($career, $actor, [
            'locale' => 'fr',
            'title' => 'Ingénieur CT',
        ]);

        return $career;
    }
}
