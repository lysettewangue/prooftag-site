<?php

declare(strict_types=1);

namespace App\Enums;

enum ResourceRegulatoryStatus: string
{
    case Current = 'current';
    case Historical = 'historical';
    case Archive = 'archive';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Archive is a public label, not Eloquent soft-delete.
     */
    public function isBusinessArchive(): bool
    {
        return $this === self::Archive;
    }
}
