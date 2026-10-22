<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactRequests\Pages;

use App\Filament\Resources\ContactRequests\ContactRequestResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

final class ListContactRequests extends ListRecords
{
    protected static string $resource = ContactRequestResource::class;

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
