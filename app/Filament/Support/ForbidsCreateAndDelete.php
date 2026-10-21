<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Illuminate\Database\Eloquent\Model;

trait ForbidsCreateAndDelete
{
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
