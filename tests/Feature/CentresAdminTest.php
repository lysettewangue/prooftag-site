<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Centres\ArchiveCentre;
use App\Actions\Centres\ImportCentre;
use App\Actions\Centres\MarkCentreTemporarilyUnavailable;
use App\Actions\Centres\TranslateCentre;
use App\Actions\Centres\UpdateCentre;
use App\Actions\Centres\VerifyCentre;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\CentreVerificationStatus;
use App\Enums\RegionCode;
use App\Enums\StaffRole;
use App\Filament\Resources\InspectionCentres\InspectionCentreResource;
use App\Models\InspectionCentre;
use App\Models\Region;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CentresAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_representative_centre_subset_is_seeded_and_regions_stay_ten(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get(InspectionCentreResource::getUrl('index'))
            ->assertOk();

        $this->assertSame(4, InspectionCentre::query()->count());
        $this->assertSame(3, InspectionCentre::query()->where('verification_status', CentreVerificationStatus::Active)->count());
        $this->assertSame(1, InspectionCentre::query()->where('verification_status', CentreVerificationStatus::Unverified)->count());
        $this->assertSame(10, Region::query()->count());
        $this->assertTrue(InspectionCentreResource::canCreate());
        $this->assertFalse(InspectionCentreResource::canDeleteAny());
    }

    public function test_there_is_no_region_admin_module(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->actingAs($admin)
            ->get('/admin/regions')
            ->assertNotFound();
    }

    public function test_technical_editor_can_open_create(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();

        $this->actingAs($editor)
            ->get(InspectionCentreResource::getUrl('create'))
            ->assertOk();
    }

    public function test_translator_can_list_but_cannot_create(): void
    {
        $translator = User::factory()->staff(StaffRole::Translator)->create();

        $this->actingAs($translator)
            ->get(InspectionCentreResource::getUrl('index'))
            ->assertOk();

        $this->assertFalse(InspectionCentreResource::canCreate());

        $this->actingAs($translator)
            ->get(InspectionCentreResource::getUrl('create'))
            ->assertForbidden();
    }

    public function test_support_can_list_but_cannot_create(): void
    {
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();

        $this->actingAs($support)
            ->get(InspectionCentreResource::getUrl('index'))
            ->assertOk();

        $this->assertFalse(InspectionCentreResource::canCreate());
    }

    public function test_communications_editor_cannot_open_centres(): void
    {
        $editor = User::factory()->staff(StaffRole::CommunicationsEditor)->create();

        $this->actingAs($editor)
            ->get(InspectionCentreResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_reviewer_cannot_open_centres(): void
    {
        $reviewer = User::factory()->staff(StaffRole::ReviewerPublisher)->create();

        $this->actingAs($reviewer)
            ->get(InspectionCentreResource::getUrl('index'))
            ->assertForbidden();
    }

    public function test_imported_centre_stays_unverified_even_if_active_is_posted(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $centre = $this->importCentre($editor, [
            'verification_status' => CentreVerificationStatus::Active->value,
        ]);

        $this->assertSame(CentreVerificationStatus::Unverified, $centre->verification_status);
        $this->assertFalse($centre->isPubliclyListed());
    }

    public function test_technical_editor_can_update_french_and_cannot_write_english(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $centre = $this->importCentre($editor);

        (new TranslateCentre)->handle($centre, $editor, [
            'locale' => 'fr',
            'name' => 'Centre Douala Bonanjo',
            'address_line' => 'Bonanjo',
        ]);

        $this->assertSame('Centre Douala Bonanjo', $centre->translationFor('fr')?->name);

        $this->expectException(AuthorizationException::class);

        (new TranslateCentre)->handle($centre, $editor, [
            'locale' => 'en',
            'name' => 'Douala Bonanjo Centre',
        ]);
    }

    public function test_translator_can_write_english_and_open_edit(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $centre = $this->importCentre($editor);

        (new TranslateCentre)->handle($centre, $translator, [
            'locale' => 'en',
            'name' => 'Douala Bonanjo Centre',
        ]);

        $this->assertSame('Douala Bonanjo Centre', $centre->translationFor('en')?->name);

        $this->actingAs($translator)
            ->get(InspectionCentreResource::getUrl('edit', ['record' => $centre]))
            ->assertOk();
    }

    public function test_technical_editor_can_verify_and_cannot_mark_unavailable(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $centre = $this->importCentre($editor);

        (new VerifyCentre)->handle($centre, $editor);

        $this->assertSame(CentreVerificationStatus::Active, $centre->fresh()?->verification_status);
        $this->assertTrue($centre->fresh()?->isPubliclyListed() ?? false);

        $this->expectException(AuthorizationException::class);

        (new MarkCentreTemporarilyUnavailable)->handle($centre->fresh(), $editor);
    }

    public function test_admin_can_archive_and_filament_cannot_hard_delete(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $super = User::factory()->staff(StaffRole::SuperAdmin)->create();
        $centre = $this->importCentre($admin);

        (new ArchiveCentre)->handle($centre, $admin);

        $this->assertSame(CentreVerificationStatus::Archived, $centre->fresh()?->verification_status);
        $this->assertFalse($admin->can('delete', $centre));
        $this->assertFalse(InspectionCentreResource::canDelete($centre));

        $this->actingAs($super);
        $this->assertFalse(InspectionCentreResource::canDelete($centre));
        $this->assertFalse(InspectionCentreResource::canForceDelete($centre));
    }

    public function test_centre_slugs_must_be_unique_per_locale(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $this->importCentre($editor, ['slug' => 'centre-douala-bonanjo']);

        $this->expectException(DomainRuleViolation::class);

        $this->importCentre($editor, [
            'operator_name' => 'Autre centre',
            'name' => 'Autre centre',
            'slug' => 'centre-douala-bonanjo',
        ]);
    }

    public function test_update_rejects_an_unknown_region(): void
    {
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $centre = $this->importCentre($editor);

        $this->expectException(DomainRuleViolation::class);

        (new UpdateCentre)->handle($centre, $editor, [
            'region_id' => 9999,
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function importCentre(User $actor, array $overrides = []): InspectionCentre
    {
        $region = Region::query()->where('code', RegionCode::Littoral)->firstOrFail();

        return (new ImportCentre)->handle($actor, array_merge([
            'region_id' => $region->id,
            'operator_name' => 'Centre Douala',
            'city' => 'Douala',
            'name' => 'Centre Douala Bonanjo',
            'slug' => 'centre-douala-bonanjo',
        ], $overrides));
    }
}
