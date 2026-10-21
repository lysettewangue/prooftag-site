<?php

declare(strict_types=1);

namespace App\Enums;

enum EquipmentLifecycle: string
{
    case Current = 'current';
    case Legacy = 'legacy';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }
}
