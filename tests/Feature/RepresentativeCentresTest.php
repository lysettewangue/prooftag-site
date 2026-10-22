<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CentreVerificationStatus;
use App\Models\InspectionCentre;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepresentativeCentresTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_small_verified_subset_leaves_one_unverified_and_invents_no_contact(): void
    {
        $this->assertSame(10, Region::query()->count());
        $this->assertSame(4, InspectionCentre::query()->count());
        $this->assertSame(3, InspectionCentre::query()->where('verification_status', CentreVerificationStatus::Active)->count());
        $this->assertSame(1, InspectionCentre::query()->where('verification_status', CentreVerificationStatus::Unverified)->count());

        foreach (InspectionCentre::query()->get() as $centre) {
            $this->assertNull($centre->phone);
            $this->assertNull($centre->email);
            $this->assertNull($centre->latitude);
            $this->assertNull($centre->longitude);
            $this->assertNull($centre->verified_by);
        }

        $this->assertTrue(
            InspectionCentre::query()->where('operator_name', 'CADCIA SARL')->where('city', 'Douala')->exists(),
        );
        $this->assertTrue(
            InspectionCentre::query()->where('operator_name', 'SATELLITE NGONO')->where('city', 'Garoua')->exists(),
        );
    }
}
