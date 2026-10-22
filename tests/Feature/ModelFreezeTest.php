<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\PageCode;
use App\Enums\ProductCode;
use App\Enums\StaffRole;
use App\Models\Page;
use App\Models\Product;
use App\Models\Region;
use App\Models\SecurityPoint;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ModelFreezeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_frozen_catalogues_stay_at_v1_counts(): void
    {
        $this->assertSame(1, SiteSetting::query()->count());
        $this->assertSame(11, Page::query()->count());
        $this->assertEqualsCanonicalizing(PageCode::codes(), Page::query()->toBase()->pluck('code')->all());
        $this->assertSame(5, Product::query()->count());
        $this->assertEqualsCanonicalizing(ProductCode::codes(), Product::query()->toBase()->pluck('code')->all());
        $this->assertFalse(DB::table('products')->where('code', 'DITROS_CT')->exists());
        $this->assertSame(12, SecurityPoint::query()->count());
        $this->assertSame(10, Region::query()->count());
        $this->assertEqualsCanonicalizing(StaffRole::codes(), Role::query()->pluck('name')->all());
        $this->assertSame(8, Role::query()->count());
    }

    public function test_the_frozen_cms_table_set_is_present(): void
    {
        foreach ([
            'site_settings',
            'pages',
            'team_members',
            'partners',
            'careers',
            'products',
            'expertises',
            'security_points',
            'equipment',
            'regions',
            'inspection_centres',
            'categories',
            'posts',
            'resources',
            'media',
            'contact_requests',
            'redirects',
        ] as $table) {
            $this->assertTrue(Schema::hasTable($table), $table);
        }

        $this->assertFalse(Schema::hasTable('locales'));
        $this->assertFalse(Schema::hasTable('ditros_ct'));
    }
}
