<?php

declare(strict_types=1);

namespace App\Filament\Resources\ActivityLog\Pages;

use App\Filament\Resources\ActivityLog\ActivityLogResource;
use Filament\Resources\Pages\ViewRecord;

final class ViewActivityLog extends ViewRecord
{
    protected static string $resource = ActivityLogResource::class;
}
