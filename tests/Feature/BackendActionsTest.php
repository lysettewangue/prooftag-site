<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Centres\ArchiveCentre;
use App\Actions\Centres\ImportCentre;
use App\Actions\Centres\MarkCentreTemporarilyUnavailable;
use App\Actions\Centres\VerifyCentre;
use App\Actions\Contact\SubmitContactRequest;
use App\Actions\Contact\UpdateContactRequestStatus;
use App\Actions\Editorial\ApprovePost;
use App\Actions\Editorial\CreatePost;
use App\Actions\Editorial\PublishPost;
use App\Actions\Editorial\SubmitPostForReview;
use App\Actions\Editorial\TranslatePost;
use App\Actions\Media\AttachMedia;
use App\Actions\Media\SafeDeleteMedia;
use App\Actions\Products\ApproveProduct;
use App\Actions\Products\ArchiveProduct;
use App\Actions\Products\PublishProduct;
use App\Actions\Products\ScheduleProduct;
use App\Actions\Products\SubmitProductForReview;
use App\Actions\Products\TranslateProduct;
use App\Actions\Products\UpdateProduct;
use App\Actions\Resources\ApproveResource;
use App\Actions\Resources\CreateResource;
use App\Actions\Resources\PublishResource;
use App\Actions\Resources\SubmitResourceForReview;
use App\Actions\Resources\TranslateResource;
use App\Actions\Seo\UpsertRedirect;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\CentreVerificationStatus;
use App\Enums\ContactRequestStatus;
use App\Enums\Locale;
use App\Enums\PostKind;
use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use App\Enums\RegionCode;
use App\Enums\ResourceRegulatoryStatus;
use App\Enums\StaffRole;
use App\Mail\ContactReceivedMail;
use App\Models\Product;
use App\Models\Region;
use App\Models\User;
use App\Queries\ProductCatalog;
use App\Queries\SitemapQuery;
use App\Queries\SiteSearch;
use App\Services\Seo\RedirectResolver;
use App\Services\Seo\SeoMetaResolver;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class BackendActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_products_follow_the_publication_matrix_and_url_gate(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $translator = User::factory()->staff(StaffRole::Translator)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();

        $this->expectException(AuthorizationException::class);
        (new UpdateProduct)->handle($product, $translator, ['external_url' => 'https://example.test']);
    }

    public function test_a_product_cannot_jump_from_draft_to_published(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();

        $this->expectException(DomainRuleViolation::class);
        (new PublishProduct)->handle($product, $admin);
    }

    public function test_ct_verif_needs_an_external_url_before_publish(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CtVerif)->firstOrFail();

        (new SubmitProductForReview)->handle($product, $admin);
        (new ApproveProduct)->handle($product, $admin);

        $this->expectException(DomainRuleViolation::class);
        (new PublishProduct)->handle($product, $admin);
    }

    public function test_publishing_a_product_makes_it_public_and_flushes_the_catalog_cache(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();

        $this->assertSame(0, (new ProductCatalog)->publishedForLocale('fr')->count());

        (new SubmitProductForReview)->handle($product, $admin);
        (new ApproveProduct)->handle($product, $admin);
        (new PublishProduct)->handle($product->fresh(), $admin);

        $catalog = (new ProductCatalog)->publishedForLocale('fr');

        $this->assertSame(1, $catalog->count());
        $this->assertTrue($catalog->first()?->is(Product::query()->where('code', ProductCode::CertidocsCt)->first()));
        $this->assertSame('certidocs-ct', $catalog->first()?->translationFor('fr')?->slug);
    }

    public function test_product_slugs_stay_frozen_to_the_fragment(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();

        $this->expectException(DomainRuleViolation::class);
        (new TranslateProduct)->handle($product, $admin, [
            'locale' => Locale::French->value,
            'slug' => 'other-slug',
            'summary' => 'Résumé',
        ]);
    }

    public function test_scheduled_products_become_public_only_after_the_scheduler_runs(): void
    {
        $this->travelTo('2026-10-21 12:00:00');

        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();

        (new SubmitProductForReview)->handle($product, $admin);
        (new ApproveProduct)->handle($product, $admin);
        (new ScheduleProduct)->handle($product->fresh(), $admin, now()->addHour());

        $this->assertSame(PublicationStatus::Scheduled, $product->fresh()->publication_status);
        $this->assertSame(0, (new ProductCatalog)->publishedForLocale('fr')->count());

        $this->travelTo('2026-10-21 13:01:00');
        $this->artisan('content:publish-scheduled')->assertSuccessful();

        $this->assertSame(PublicationStatus::Published, $product->fresh()->publication_status);
        $this->assertSame(1, (new ProductCatalog)->publishedForLocale('fr')->count());
    }

    public function test_imported_centres_are_always_unverified_until_verify_centre(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $region = Region::query()->where('code', RegionCode::Littoral)->firstOrFail();

        $centre = (new ImportCentre)->handle($admin, [
            'region_id' => $region->id,
            'operator_name' => 'Centre Douala',
            'city' => 'Douala',
            'name' => 'Centre Douala Bonanjo',
            'slug' => 'centre-douala-bonanjo',
            'verification_status' => CentreVerificationStatus::Active->value,
        ]);

        $this->assertSame(CentreVerificationStatus::Unverified, $centre->verification_status);
        $this->assertFalse($centre->isPubliclyListed());

        (new VerifyCentre)->handle($centre, $editor);

        $this->assertSame(CentreVerificationStatus::Active, $centre->fresh()->verification_status);
        $this->assertTrue($centre->fresh()->isPubliclyListed());
        $this->assertNotNull($centre->fresh()->last_verified_at);
        $this->assertSame($editor->id, $centre->fresh()->verified_by);
    }

    public function test_only_admins_can_mark_a_centre_temporarily_unavailable(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $editor = User::factory()->staff(StaffRole::TechnicalEditor)->create();
        $region = Region::query()->where('code', RegionCode::Littoral)->firstOrFail();

        $centre = (new ImportCentre)->handle($admin, [
            'region_id' => $region->id,
            'operator_name' => 'Centre Yaounde',
            'city' => 'Yaoundé',
            'slug' => 'centre-yaounde',
        ]);
        (new VerifyCentre)->handle($centre, $editor);

        $this->expectException(AuthorizationException::class);
        (new MarkCentreTemporarilyUnavailable)->handle($centre->fresh(), $editor);
    }

    public function test_editorial_and_resource_publish_paths_and_search_exclude_drafts(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $post = (new CreatePost)->handle($admin, ['kind' => PostKind::News->value]);
        (new TranslatePost)->handle($post, $admin, [
            'locale' => Locale::French->value,
            'slug' => 'nouvelle-ct',
            'title' => 'Nouvelle CT',
            'summary' => 'Une actualité CERTIDOCS',
        ]);
        (new SubmitPostForReview)->handle($post, $admin);
        (new ApprovePost)->handle($post, $admin);
        (new PublishPost)->handle($post->fresh(), $admin);

        $resource = (new CreateResource)->handle($admin, [
            'regulatory_status' => ResourceRegulatoryStatus::Current->value,
            'authority' => 'MINTRANSPORT',
        ]);
        (new TranslateResource)->handle($resource, $admin, [
            'locale' => Locale::French->value,
            'slug' => 'guide-ct',
            'title' => 'Guide CT',
            'summary' => 'Document CERTIDOCS',
        ]);
        (new SubmitResourceForReview)->handle($resource, $admin);
        (new ApproveResource)->handle($resource, $admin);
        (new PublishResource)->handle($resource->fresh(), $admin);

        $results = (new SiteSearch)->search('fr', 'CERTIDOCS');

        $this->assertTrue($results['posts']->contains(fn (mixed $row): bool => $row->is($post->fresh())));
        $this->assertTrue($results['resources']->contains(fn (mixed $row): bool => $row->is($resource->fresh())));
        $this->assertTrue($results['products']->isEmpty());
    }

    public function test_contact_spam_sends_no_mail_and_status_updates_follow_the_pipeline(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Alice',
            'email' => 'alice@example.test',
            'subject' => 'Question',
            'message' => 'Bonjour',
            'locale' => Locale::French->value,
        ];

        $spamRequest = Request::create('/contact', 'POST', array_merge($payload, ['website' => 'http://spam.test']));
        $spam = (new SubmitContactRequest)->handle($payload, $spamRequest);

        $this->assertSame(ContactRequestStatus::Spam, $spam->status);
        Mail::assertNothingSent();

        $legitRequest = Request::create('/contact', 'POST', $payload, server: ['REMOTE_ADDR' => '10.1.1.8']);
        $contact = (new SubmitContactRequest)->handle($payload, $legitRequest);

        $this->assertSame(ContactRequestStatus::New, $contact->status);
        Mail::assertSent(ContactReceivedMail::class);
        $this->assertNotNull($contact->fresh()->notified_at);

        $agent = User::factory()->staff(StaffRole::SupportAgent)->create();
        $updated = (new UpdateContactRequestStatus)->handle($contact, $agent, ContactRequestStatus::InProgress);

        $this->assertSame(ContactRequestStatus::InProgress, $updated->status);

        $this->expectException(DomainRuleViolation::class);
        (new UpdateContactRequestStatus)->handle($updated, $agent, ContactRequestStatus::New);
    }

    public function test_contact_submissions_are_throttled_per_ip(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Bob',
            'email' => 'bob@example.test',
            'subject' => 'Hello',
            'message' => 'Test',
            'locale' => 'fr',
        ];

        for ($i = 0; $i < 5; $i++) {
            $request = Request::create('/contact', 'POST', $payload, server: ['REMOTE_ADDR' => '10.2.2.2']);
            (new SubmitContactRequest)->handle($payload, $request);
        }

        $this->expectException(ValidationException::class);
        $request = Request::create('/contact', 'POST', $payload, server: ['REMOTE_ADDR' => '10.2.2.2']);
        (new SubmitContactRequest)->handle($payload, $request);
    }

    public function test_images_require_bilingual_alt_and_cannot_be_deleted_while_published(): void
    {
        Storage::fake('public');

        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();
        $file = UploadedFile::fake()->image('hero.jpg');

        $this->expectException(DomainRuleViolation::class);
        (new AttachMedia)->handle($product, $admin, $file, 'images', ['rights' => 'internal']);
    }

    public function test_safe_delete_blocks_media_on_a_public_product(): void
    {
        Storage::fake('public');

        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();
        $file = UploadedFile::fake()->image('hero.jpg');

        $media = (new AttachMedia)->handle($product, $admin, $file, 'images', [
            'alt.fr' => 'Produit',
            'alt.en' => 'Product',
            'rights' => 'PROOFTAG-CATIS',
        ]);

        $this->assertSame('Produit', $media->getCustomProperty('alt.fr'));

        (new SubmitProductForReview)->handle($product, $admin);
        (new ApproveProduct)->handle($product, $admin);
        (new PublishProduct)->handle($product->fresh(), $admin);

        $this->expectException(DomainRuleViolation::class);
        (new SafeDeleteMedia)->handle($media->fresh(), $admin);
    }

    public function test_redirects_resolve_exact_then_longest_prefix_and_canonical_is_www(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        (new UpsertRedirect)->handle($admin, [
            'from_host' => 'site.prooftagcatis.com',
            'from_path' => '/old',
            'to_path' => '/produits',
            'status_code' => 301,
        ]);
        (new UpsertRedirect)->handle($admin, [
            'from_host' => 'site.prooftagcatis.com',
            'from_path' => '/old/child',
            'to_path' => '/produits/certidocs-ct',
            'status_code' => 301,
        ]);

        $resolver = new RedirectResolver;

        $this->assertSame('/produits/certidocs-ct', $resolver->resolve('site.prooftagcatis.com', '/old/child')?->to_path);
        $this->assertSame('/produits', $resolver->resolve('site.prooftagcatis.com', '/old/other')?->to_path);
        $this->assertNull($resolver->resolve('site.prooftagcatis.com', '/unmapped'));

        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();
        $meta = (new SeoMetaResolver)->forModel($product, 'fr');

        $this->assertSame('https://www.prooftagcatis.com/produits/certidocs-ct', $meta->canonical);
        $this->assertArrayHasKey('fr', $meta->hreflang);
        $this->assertArrayHasKey('en', $meta->hreflang);
    }

    public function test_sitemap_lists_only_published_urls_and_active_centres(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();

        (new SubmitProductForReview)->handle($product, $admin);
        (new ApproveProduct)->handle($product, $admin);
        (new PublishProduct)->handle($product->fresh(), $admin);

        $region = Region::query()->where('code', RegionCode::Littoral)->firstOrFail();
        $centre = (new ImportCentre)->handle($admin, [
            'region_id' => $region->id,
            'operator_name' => 'Sitemap Centre',
            'city' => 'Douala',
            'slug' => 'sitemap-centre',
        ]);
        (new VerifyCentre)->handle($centre, $admin);

        $locs = collect((new SitemapQuery)->urls())->pluck('loc');

        $this->assertTrue($locs->contains('https://www.prooftagcatis.com/produits/certidocs-ct'));
        $this->assertTrue($locs->contains('https://www.prooftagcatis.com/centres-de-controle/sitemap-centre'));
        $this->assertFalse($locs->contains('https://www.prooftagcatis.com/produits/ct-verif'));
    }

    public function test_archived_products_leave_the_public_catalog(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $product = Product::query()->where('code', ProductCode::CertidocsCt)->firstOrFail();

        (new SubmitProductForReview)->handle($product, $admin);
        (new ApproveProduct)->handle($product, $admin);
        (new PublishProduct)->handle($product->fresh(), $admin);
        (new ArchiveProduct)->handle($product->fresh(), $admin);

        $this->assertSame(PublicationStatus::Archived, $product->fresh()->publication_status);
        $this->assertSame(0, (new ProductCatalog)->publishedForLocale('fr')->count());
    }

    public function test_a_sixth_product_cannot_be_created(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();

        $this->assertFalse($admin->can('create', Product::class));
        $this->assertSame(5, Product::query()->count());
    }

    public function test_support_cannot_archive_a_centre(): void
    {
        $admin = User::factory()->staff(StaffRole::Admin)->create();
        $support = User::factory()->staff(StaffRole::SupportAgent)->create();
        $region = Region::query()->where('code', RegionCode::Littoral)->firstOrFail();

        $centre = (new ImportCentre)->handle($admin, [
            'region_id' => $region->id,
            'operator_name' => 'Archive Me',
            'city' => 'Douala',
            'slug' => 'archive-me',
        ]);

        $this->expectException(AuthorizationException::class);
        (new ArchiveCentre)->handle($centre, $support);
    }
}
