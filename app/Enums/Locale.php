<?php

declare(strict_types=1);

namespace App\Enums;

enum Locale: string
{
    case French = 'fr';
    case English = 'en';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isDefault(): bool
    {
        return $this === self::French;
    }
}
