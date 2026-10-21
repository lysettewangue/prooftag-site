<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Illuminate\Database\Eloquent\Model;

trait AuthorizesCmsResource
{
    public static function canEdit(Model $record): bool
    {
        return static::can('update', $record) || static::can('translate', $record);
    }
}
