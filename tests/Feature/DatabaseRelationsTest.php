<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\PageCode;
use App\Enums\ProductCode;
use App\Enums\RegionCode;
use App\Models\InspectionCentre;
use App\Models\Page;
use App\Models\Product;
use App\Models\Region;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseRelationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_product_translations_cascade_when_the_product_is_force_deleted(): void
    {
        $product = Product::query()->where('code', ProductCode::VideoEnforcement)->firstOrFail();
        $id = $product->id;

        $this->assertGreaterThan(0, DB::table('product_translations')->where('product_id', $id)->count());

        $product->forceDelete();

        $this->assertSame(0, DB::table('product_translations')->where('product_id', $id)->count());
    }

    public function test_page_translations_cascade_when_the_page_is_force_deleted(): void
    {
        $page = Page::query()->where('code', PageCode::Privacy)->firstOrFail();
        $id = $page->id;

        $page->forceDelete();

        $this->assertSame(0, DB::table('page_translations')->where('page_id', $id)->count());
    }

    public function test_deleting_a_region_with_centres_is_restricted(): void
    {
        $region = Region::query()->where('code', RegionCode::Littoral)->firstOrFail();

        $this->assertTrue(
            InspectionCentre::query()->where('region_id', $region->id)->exists(),
        );

        $this->expectException(QueryException::class);
        $region->delete();
    }

    public function test_redirect_host_check_rejects_unknown_hosts(): void
    {
        $this->expectException(QueryException::class);

        DB::table('redirects')->insert([
            'from_host' => 'evil.example',
            'from_path' => '/x',
            'to_path' => '/',
            'status_code' => 301,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_legacy_redirect_register_is_seeded(): void
    {
        $this->assertSame(23, DB::table('redirects')->where('is_active', 1)->count());
        $this->assertSame(
            '/controle-technique',
            DB::table('redirects')->where('from_path', '/solutions/ditros-ct')->value('to_path'),
        );
    }
}
