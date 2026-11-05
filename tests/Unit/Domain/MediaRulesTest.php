<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Media\MediaRules;
use Tests\TestCase;

class MediaRulesTest extends TestCase
{
    public function test_allowed_mimes_exclude_executables(): void
    {
        $this->assertTrue(MediaRules::isAllowed('image/jpeg'));
        $this->assertTrue(MediaRules::isAllowed('image/png'));
        $this->assertTrue(MediaRules::isAllowed('application/pdf'));
        $this->assertFalse(MediaRules::isAllowed('application/x-msdownload'));
        $this->assertFalse(MediaRules::isAllowed('application/x-php'));
        $this->assertFalse(MediaRules::isAllowed('text/html'));
    }

    public function test_image_detection_and_size_budget(): void
    {
        $this->assertTrue(MediaRules::isImage('image/webp'));
        $this->assertFalse(MediaRules::isImage('application/pdf'));
        $this->assertSame(8 * 1024 * 1024, MediaRules::maxBytes());
        $this->assertContains('image/svg+xml', MediaRules::imageMimes());
    }
}
