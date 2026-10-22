<?php

declare(strict_types=1);

namespace App\Filament\Resources\ActivityLog\Pages;

use App\Filament\Resources\ActivityLog\ActivityLogResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

final class ListActivityLog extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
