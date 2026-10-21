<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Centres\CentreVisibility;
use App\Domain\Communication\ContactPipeline;
use App\Domain\Products\ProductPublishRules;
use App\Domain\Publication\Visibility;
use App\Domain\Translation\LocaleAvailability;
use App\Enums\CentreVerificationStatus;
use App\Enums\ContactRequestStatus;
use App\Enums\ProductCode;
use App\Enums\PublicationStatus;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PublicationRulesTest extends TestCase
{
    public function test_only_published_rows_with_a_past_published_at_are_public(): void
    {
        $now = new DateTimeImmutable('2026-10-21 12:00:00');

        $this->assertFalse(Visibility::isPubliclyVisible(PublicationStatus::Draft, $now, $now));
        $this->assertFalse(Visibility::isPubliclyVisible(PublicationStatus::Scheduled, $now, $now));
        $this->assertFalse(Visibility::isPubliclyVisible(PublicationStatus::Archived, $now, $now));
        $this->assertFalse(Visibility::isPubliclyVisible(PublicationStatus::Published, null, $now));
        $this->assertFalse(Visibility::isPubliclyVisible(
            PublicationStatus::Published,
            new DateTimeImmutable('2026-10-21 13:00:00'),
            $now,
        ));
        $this->assertTrue(Visibility::isPubliclyVisible(PublicationStatus::Published, $now, $now));
    }

    public function test_publication_transitions_match_the_approved_matrix(): void
    {
        $this->assertTrue(PublicationStatus::Draft->canTransitionTo(PublicationStatus::UnderReview));
        $this->assertFalse(PublicationStatus::Draft->canTransitionTo(PublicationStatus::Published));
        $this->assertTrue(PublicationStatus::Approved->canTransitionTo(PublicationStatus::Published));
        $this->assertTrue(PublicationStatus::Scheduled->canTransitionTo(PublicationStatus::Published));
        $this->assertFalse(PublicationStatus::Published->canTransitionTo(PublicationStatus::Draft));
        $this->assertTrue(PublicationStatus::Published->requiresPublishPermission());
        $this->assertFalse(PublicationStatus::Draft->requiresPublishPermission());
    }

    public function test_a_locale_url_requires_both_a_public_base_and_a_translation(): void
    {
        $this->assertFalse(LocaleAvailability::hasPublicLocale(true, false));
        $this->assertFalse(LocaleAvailability::hasPublicLocale(false, true));
        $this->assertTrue(LocaleAvailability::hasPublicLocale(true, true));
        $this->assertTrue(LocaleAvailability::isSupported('fr'));
        $this->assertFalse(LocaleAvailability::isSupported('de'));
    }

    public function test_only_active_centres_are_public_and_unverified_cannot_skip_to_public(): void
    {
        $this->assertTrue(CentreVisibility::isPubliclyListed(CentreVerificationStatus::Active));
        $this->assertFalse(CentreVisibility::isPubliclyListed(CentreVerificationStatus::Unverified));
        $this->assertFalse(CentreVisibility::isPubliclyListed(CentreVerificationStatus::TemporarilyUnavailable));
        $this->assertFalse(CentreVisibility::showsMapPin(CentreVerificationStatus::Active, null, '9.7'));
        $this->assertTrue(CentreVisibility::showsMapPin(CentreVerificationStatus::Active, '4.05', '9.7'));
        $this->assertTrue(CentreVerificationStatus::Unverified->canTransitionTo(CentreVerificationStatus::Active));
        $this->assertFalse(CentreVerificationStatus::Archived->canTransitionTo(CentreVerificationStatus::Active));
    }

    public function test_ct_verif_and_doser_require_an_external_url_before_publish(): void
    {
        $this->assertTrue(ProductPublishRules::mayEnterPublished(ProductCode::CertidocsCt, null));
        $this->assertFalse(ProductPublishRules::mayEnterPublished(ProductCode::CtVerif, null));
        $this->assertFalse(ProductPublishRules::mayEnterPublished(ProductCode::Doser, ''));
        $this->assertTrue(ProductPublishRules::mayEnterPublished(ProductCode::Doser, 'https://doser.example'));
        $this->assertTrue(ProductCode::CtVerif->requiresExternalUrlBeforePublish());
        $this->assertFalse(ProductCode::VideoEnforcement->requiresExternalUrlBeforePublish());
    }

    public function test_spam_contact_requests_do_not_dispatch_mail(): void
    {
        $this->assertTrue(ContactPipeline::dispatchesInboundMail(ContactRequestStatus::New));
        $this->assertFalse(ContactPipeline::dispatchesInboundMail(ContactRequestStatus::Spam));
        $this->assertTrue(ContactRequestStatus::New->canTransitionTo(ContactRequestStatus::Spam));
        $this->assertFalse(ContactRequestStatus::Closed->canTransitionTo(ContactRequestStatus::New));
    }
}
