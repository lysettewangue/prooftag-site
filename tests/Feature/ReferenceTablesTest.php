<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CentreVerificationStatus;
use App\Enums\ControlledStatusSet;
use App\Enums\PublicationStatus;
use App\Enums\RegionCode;
use App\Models\ControlledStatus;
use App\Models\Region;
use App\Models\RegionTranslation;
use Database\Seeders\ControlledStatusSeeder;
use Database\Seeders\RegionSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReferenceTablesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([RegionSeeder::class, ControlledStatusSeeder::class]);
    }

    public function test_ten_cameroon_regions_are_seeded_with_fr_and_en(): void
    {
        $this->assertSame(10, Region::query()->count());
        $this->assertEqualsCanonicalizing(
            RegionCode::codes(),
            Region::query()->toBase()->pluck('code')->all(),
        );
        $this->assertSame(20, RegionTranslation::query()->count());

        $adamaoua = Region::query()->where('code', RegionCode::Adamaoua)->firstOrFail();
        $this->assertSame('Adamaoua', $adamaoua->translations()->where('locale', 'fr')->value('name'));
        $this->assertSame('adamawa', $adamaoua->translations()->where('locale', 'en')->value('slug'));

        foreach (Region::query()->with('translations')->get() as $region) {
            $this->assertEqualsCanonicalizing(['en', 'fr'], $region->translations->pluck('locale')->all());
        }
    }

    public function test_region_codes_are_unique(): void
    {
        $this->expectException(QueryException::class);

        Region::query()->create(['code' => RegionCode::Littoral->value]);
    }

    public function test_region_translation_slugs_are_unique_per_locale(): void
    {
        $this->expectException(QueryException::class);

        RegionTranslation::query()
            ->where('locale', 'en')
            ->where('slug', 'adamawa')
            ->update(['slug' => 'littoral']);
    }

    public function test_controlled_status_set_and_code_are_unique(): void
    {
        $this->expectException(QueryException::class);

        ControlledStatus::query()->create([
            'set' => ControlledStatusSet::Publication,
            'code' => PublicationStatus::Draft->value,
            'sort_order' => 99,
        ]);
    }

    public function test_controlled_status_sets_match_the_approved_vocabularies(): void
    {
        foreach (ControlledStatusSet::cases() as $set) {
            $codes = ControlledStatus::query()
                ->where('set', $set)
                ->orderBy('sort_order')
                ->pluck('code')
                ->all();

            $this->assertSame($set->codes(), $codes, $set->value);
        }

        $this->assertContains(PublicationStatus::Published->value, PublicationStatus::codes());
        $this->assertContains(CentreVerificationStatus::Unverified->value, CentreVerificationStatus::codes());
        $this->assertSame(
            array_sum(array_map(
                static fn (ControlledStatusSet $set): int => count($set->codes()),
                ControlledStatusSet::cases(),
            )),
            ControlledStatus::query()->count(),
        );
    }
}
