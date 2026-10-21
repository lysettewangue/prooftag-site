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
}
