<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ExpertiseCode;
use App\Enums\PageCode;
use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Enums\RegionCode;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Expertise;
use App\Models\InspectionCentre;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Product;
use App\Models\Region;
use App\Models\Resource;
use App\Models\SecurityPoint;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseStructureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_verified_reference_rows_are_seeded_and_unpublished(): void
    {
        $this->assertSame(1, SiteSetting::query()->count());
        $this->assertSame('info@prooftagcatis.com', SiteSetting::query()->findOrFail(1)->email);
        $this->assertNull(SiteSetting::query()->findOrFail(1)->social_linkedin);

        $this->assertSame(11, Page::query()->count());
        $this->assertEqualsCanonicalizing(PageCode::codes(), Page::query()->toBase()->pluck('code')->all());
        $this->assertSame(5, Product::query()->count());
        $this->assertEqualsCanonicalizing(ProductCode::codes(), Product::query()->toBase()->pluck('code')->all());
        $this->assertSame(5, Expertise::query()->count());
        $this->assertEqualsCanonicalizing(ExpertiseCode::codes(), Expertise::query()->toBase()->pluck('code')->all());
        $this->assertSame(12, SecurityPoint::query()->count());
        $this->assertEqualsCanonicalizing(range(1, 12), SecurityPoint::query()->orderBy('sort_order')->pluck('sort_order')->all());
        $this->assertSame(10, Region::query()->count());

        $this->assertSame(0, TeamMember::query()->count());
        $this->assertSame(0, Partner::query()->count());
        $this->assertSame(4, Equipment::query()->count());
        $this->assertSame(1, Resource::query()->count());
        $this->assertSame(4, InspectionCentre::query()->count());
        $this->assertSame(2, Category::query()->count());
        $this->assertSame(2, Post::query()->count());

        $this->assertTrue(Page::query()->where('publication_status', '!=', PublicationStatus::Draft)->doesntExist());
        $this->assertTrue(Product::query()->where('publication_status', '!=', PublicationStatus::Draft)->doesntExist());
        $this->assertTrue(Expertise::query()->where('publication_status', '!=', PublicationStatus::Draft)->doesntExist());
        $this->assertTrue(SecurityPoint::query()->where('publication_status', '!=', PublicationStatus::Draft)->doesntExist());
    }

    public function test_product_translation_slugs_match_the_frozen_fragment(): void
    {
        foreach (Product::query()->with('translations')->get() as $product) {
            $this->assertNotNull($product->code);
            foreach ($product->translations as $translation) {
                $this->assertSame($product->slug_fragment, $translation->slug, $product->code->value);
            }
        }
    }

    public function test_expertise_titles_do_not_copy_product_names(): void
    {
        $productNames = Product::query()->pluck('public_name')->all();
        $titles = DB::table('expertise_translations')->pluck('title')->all();

        foreach ($titles as $title) {
            $this->assertNotContains($title, $productNames);
        }
    }

    public function test_there_is_no_ditros_product_and_no_sixth_code(): void
    {
        $this->assertFalse(DB::table('products')->where('code', 'DITROS_CT')->exists());

        $this->expectException(QueryException::class);

        DB::table('products')->insert([
            'code' => 'DITROS_CT',
            'public_name' => 'DITROS-CT',
            'sort_order' => 6,
            'slug_fragment' => 'ditros-ct',
            'publication_status' => PublicationStatus::Draft->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_a_thirteenth_security_point_is_rejected(): void
    {
        $this->expectException(QueryException::class);

        DB::table('security_points')->insert([
            'sort_order' => 13,
            'publication_status' => PublicationStatus::Draft->value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_site_settings_remain_a_singleton(): void
    {
        $this->expectException(QueryException::class);

        DB::table('site_settings')->insert([
            'id' => 2,
            'legal_name' => 'Other',
            'brand' => 'Other',
            'building' => 'Other',
            'city' => 'Douala',
            'country' => 'Cameroun',
            'postal_box' => 'BP 0',
            'telephone' => '+237 000 00 00 00',
            'email' => 'other@example.com',
            'canonical_url' => 'https://example.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_centre_region_foreign_key_restricts_delete(): void
    {
        $region = Region::query()->where('code', RegionCode::Littoral)->firstOrFail();

        InspectionCentre::query()->create([
            'region_id' => $region->id,
            'operator_name' => 'Test Centre',
            'city' => 'Douala',
        ]);

        $this->expectException(QueryException::class);

        $region->delete();
    }

    public function test_page_translations_cascade_on_parent_delete(): void
    {
        $page = Page::query()->where('code', PageCode::About)->firstOrFail();
        $id = $page->id;

        $page->forceDelete();

        $this->assertSame(0, DB::table('page_translations')->where('page_id', $id)->count());
    }

    public function test_cms_and_media_tables_exist(): void
    {
        foreach ([
            'users',
            'roles',
            'permissions',
            'activity_log',
            'site_settings',
            'pages',
            'team_members',
            'partners',
            'careers',
            'products',
            'expertises',
            'product_expertise',
            'security_points',
            'equipment',
            'regions',
            'inspection_centres',
            'categories',
            'posts',
            'post_category',
            'post_product',
            'post_expertise',
            'resources',
            'resource_product',
            'media',
            'contact_requests',
            'redirects',
        ] as $table) {
            $this->assertTrue(Schema::hasTable($table), $table);
        }
    }

    public function test_redirects_are_unique_per_host_and_path(): void
    {
        DB::table('redirects')->insert([
            'from_host' => 'www.prooftagcatis.com',
            'from_path' => '/old',
            'to_path' => '/a-propos',
            'status_code' => 301,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->expectException(QueryException::class);

        DB::table('redirects')->insert([
            'from_host' => 'www.prooftagcatis.com',
            'from_path' => '/old',
            'to_path' => '/contact',
            'status_code' => 301,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
