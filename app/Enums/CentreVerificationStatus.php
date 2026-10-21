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
}
