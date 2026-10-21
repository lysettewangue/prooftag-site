<?php

declare(strict_types=1);

namespace App\Domain\Publication;

use App\Enums\PublicationStatus;
use DateTimeInterface;

final class Visibility
{
    /**
     * Public indexes and URLs require Published plus published_at in the past.
     * Scheduled rows stay hidden until PublishScheduledContent moves them to Published.
     */
    public static function isPubliclyVisible(
        PublicationStatus $status,
        ?DateTimeInterface $publishedAt,
        DateTimeInterface $now,
    ): bool {
        if (! $status->isPublicState()) {
            return false;
        }

        if ($publishedAt === null) {
            return false;
        }

        return $publishedAt->getTimestamp() <= $now->getTimestamp();
    }
}
