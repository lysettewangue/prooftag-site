<?php

declare(strict_types=1);

namespace App\Filament\Resources\MediaLibrary\Pages;

use App\Filament\Resources\MediaLibrary\MediaLibraryResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

final class ListMediaLibrary extends ListRecords
{
    protected static string $resource = MediaLibraryResource::class;

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
