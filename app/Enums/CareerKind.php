<?php

declare(strict_types=1);

namespace App\Enums;

enum CareerKind: string
{
    case Job = 'job';
    case Internship = 'internship';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }
}
