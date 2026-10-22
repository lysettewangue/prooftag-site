<?php

declare(strict_types=1);

namespace App\Filament\Resources\InspectionCentres\Pages;

use App\Filament\Resources\InspectionCentres\InspectionCentreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListInspectionCentres extends ListRecords
{
    protected static string $resource = InspectionCentreResource::class;

    /**
     * @return array<int, CreateAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
