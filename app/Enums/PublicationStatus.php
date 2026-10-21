<?php

declare(strict_types=1);

namespace App\Enums;

enum PublicationStatus: string
{
    case Draft = 'draft';
    case UnderReview = 'under_review';
    case Approved = 'approved';
    case Scheduled = 'scheduled';
    case Published = 'published';
    case Archived = 'archived';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isPublicState(): bool
    {
        return $this === self::Published;
    }

    /**
     * @return list<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Draft => [self::UnderReview, self::Archived],
            self::UnderReview => [self::Draft, self::Approved, self::Archived],
            self::Approved => [self::UnderReview, self::Scheduled, self::Published, self::Archived],
            self::Scheduled => [self::Published, self::Approved, self::Archived],
            self::Published => [self::Archived],
            self::Archived => [self::Draft],
        };
    }

    public function canTransitionTo(self $to): bool
    {
        return in_array($to, $this->allowedTransitions(), true);
    }

    public function requiresPublishPermission(): bool
    {
        return in_array($this, [self::Scheduled, self::Published, self::Archived], true);
    }
}
