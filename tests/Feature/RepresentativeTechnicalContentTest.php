<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\EquipmentLifecycle;
use App\Enums\PublicationStatus;
use App\Enums\ResourceRegulatoryStatus;
use App\Models\Equipment;
use App\Models\Resource;
use App\Models\SecurityPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepresentativeTechnicalContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_security_points_stay_twelve_drafts_with_rewrite_body(): void
    {
        $this->assertSame(12, SecurityPoint::query()->count());
        $this->assertTrue(SecurityPoint::query()->where('publication_status', '!=', PublicationStatus::Draft)->doesntExist());

        $fr = SecurityPoint::query()->orderBy('sort_order')->firstOrFail()->translationFor('fr');
        $this->assertNotNull($fr);
        $this->assertStringContainsString('AUD-001', (string) $fr->body);
    }

    public function test_equipment_includes_current_and_legacy_drafts(): void
    {
        $this->assertSame(4, Equipment::query()->count());
        $this->assertSame(3, Equipment::query()->where('lifecycle', EquipmentLifecycle::Current)->count());
        $this->assertSame(1, Equipment::query()->where('lifecycle', EquipmentLifecycle::Legacy)->count());
        $this->assertTrue(Equipment::query()->where('publication_status', '!=', PublicationStatus::Draft)->doesntExist());
    }

    public function test_official_resources_have_real_pdfs_and_are_published(): void
    {
        $resources = Resource::query()->with(['translations', 'media'])->get();

        $this->assertCount(3, $resources);
        $this->assertTrue($resources->every(fn (Resource $resource) => $resource->publication_status === PublicationStatus::Published));
        $this->assertTrue($resources->every(fn (Resource $resource) => $resource->relates_to_ct));
        $this->assertTrue($resources->every(fn (Resource $resource) => $resource->mayEnterPublished()));
        $this->assertTrue($resources->every(fn (Resource $resource) => $resource->getFirstMedia('documents')?->size > 1024));
        $this->assertTrue($resources->contains(fn (Resource $resource) => $resource->regulatory_status === ResourceRegulatoryStatus::Historical));
        $this->assertTrue($resources->contains(fn (Resource $resource) => $resource->translationFor('fr')?->slug === 'loi-2022-007-protection-patrimoine-routier'));
    }
}
