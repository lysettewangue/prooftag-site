<?php

declare(strict_types=1);

namespace App\Enums;

enum ContactRequestStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Responded = 'responded';
    case Closed = 'closed';
    case Spam = 'spam';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }
}
