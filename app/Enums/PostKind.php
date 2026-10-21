<?php

declare(strict_types=1);

namespace App\Enums;

enum PostKind: string
{
    case News = 'news';
    case Article = 'article';

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_column(self::cases(), 'value');
    }
}
