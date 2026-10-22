<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Corporate\CreatePartner;
use App\Actions\Corporate\TranslatePartner;
use App\Actions\Corporate\UpdatePartner;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PublicationStatus;
use App\Enums\StaffRole;
use App\Filament\Resources\Partners\PartnerResource;
use App\Models\Partner;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnersAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_empty_partner_list_is_valid(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(PartnerResource::getUrl('index'))
            ->assertOk();

        $this->assertSame(0, Partner::query()->count());
        $this->assertTrue(PartnerResource::canCreate());
        $this->assertFalse(PartnerResource::canDeleteAny());
    }

    public function test_communications_editor_can_open_create(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(PartnerResource::getUrl('create'))
            ->assertOk();
    }

    public function test_translator_can_list_but_cannot_create(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(PartnerResource::getUrl('index'))
            ->assertOk();

        $this->assertFalse(PartnerResource::canCreate());

        $this->actingAs($translator)
            ->get(PartnerResource::getUrl('create'))
            ->assertForbidden();
    }

    public function test_reviewer_can_open_partners(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(PartnerResource::getUrl('index'))
            ->assertOk();
    }

    public function test_technical_editor_cannot_open_partners(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $this->actingAs($editor)
            ->get(PartnerResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_support_cannot_open_partners(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(PartnerResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_communications_editor_can_create_french_draft(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $partner = $this->makePartner($editor);

        $this->assertSame(PublicationStatus::Draft, $partner->publication_status);
        $this->assertSame('MINT', $partner->name);
        $this->assertSame('https://minatd.gov.cm', $partner->url);
        $this->assertSame('mint', $partner->translationFor('fr')?->slug);
        $this->assertNull($partner->translationFor('fr')?->blurb);
    }

    public function test_communications_editor_cannot_write_english(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $partner = $this->makePartner($editor);

        $this->expectException(AuthorizationException::class);

        (new TranslatePartner)->handle($partner, $editor, [
            'locale' => 'en',
            'blurb' => 'Ministry of Transport',
        ]);
    }

    public function test_translator_can_write_english_and_open_edit(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $partner = $this->makePartner($editor);

        (new TranslatePartner)->handle($partner, $translator, [
            'locale' => 'en',
            'blurb' => 'Ministry of Transport',
        ]);

        $this->assertSame('Ministry of Transport', $partner->translationFor('en')?->blurb);
        $this->assertSame('mint', $partner->translationFor('en')?->slug);

        $this->actingAs($translator)
            ->get(PartnerResource::getUrl('edit', ['record' => $partner]))
            ->assertOk();
    }

    public function test_partner_cannot_be_hard_deleted(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $partner = $this->makePartner($admin);

        $this->assertFalse($admin->can('delete', $partner));
        $this->assertFalse(PartnerResource::canDelete($partner));

        $this->actingAs($super);
        $this->assertFalse(PartnerResource::canDelete($partner));
        $this->assertFalse(PartnerResource::canForceDelete($partner));
    }

    public function test_partner_slugs_must_be_unique_per_locale(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $this->makePartner($editor);
        $second = (new CreatePartner)->handle($editor, [
            'name' => 'Prooftag SAS',
            'sort_order' => 2,
        ]);

        $this->expectException(DomainRuleViolation::class);

        (new TranslatePartner)->handle($second, $editor, [
            'locale' => 'fr',
            'slug' => 'mint',
        ]);
    }

    public function test_empty_url_is_stored_as_null(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();
        $partner = (new CreatePartner)->handle($editor, [
            'name' => 'Prooftag SAS',
            'sort_order' => 2,
            'url' => '',
        ]);

        $this->assertNull($partner->url);

        $updated = (new UpdatePartner)->handle($partner, $editor, ['url' => '   ']);

        $this->assertNull($updated->url);
    }

    public function test_invalid_url_is_rejected(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->expectException(DomainRuleViolation::class);

        (new CreatePartner)->handle($editor, [
            'name' => 'Prooftag SAS',
            'url' => 'not-a-url',
        ]);
    }

    private function makePartner(User $actor, string $name = 'MINT'): Partner
    {
        $partner = (new CreatePartner)->handle($actor, [
            'name' => $name,
            'sort_order' => 1,
            'url' => 'https://minatd.gov.cm',
        ]);

        (new TranslatePartner)->handle($partner, $actor, [
            'locale' => 'fr',
        ]);

        return $partner;
    }
}
