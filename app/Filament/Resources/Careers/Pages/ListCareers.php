<?php

declare(strict_types=1);

namespace App\Filament\Resources\Careers\Pages;

use App\Filament\Resources\Careers\CareerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListCareers extends ListRecords
{
    protected static string $resource = CareerResource::class;

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
