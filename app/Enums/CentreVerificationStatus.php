<?php

declare(strict_types=1);

namespace App\Enums;

enum CentreVerificationStatus: string
{
    case Unverified = 'unverified';
    case Active = 'active';
    case TemporarilyUnavailable = 'temporarily_unavailable';
    case Archived = 'archived';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isPubliclyListed(): bool
    {
        return $this === self::Active;
    }

    /**
     * @return list<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Unverified => [self::Active, self::TemporarilyUnavailable, self::Archived],
            self::Active => [self::TemporarilyUnavailable, self::Archived],
            self::TemporarilyUnavailable => [self::Active, self::Archived],
            self::Archived => [self::Unverified],
        };
    }

    public function canTransitionTo(self $to): bool
    {
        return in_array($to, $this->allowedTransitions(), true);
    }

    public function requiresVerifyPermission(): bool
    {
        return $this === self::Active;
    }
}
