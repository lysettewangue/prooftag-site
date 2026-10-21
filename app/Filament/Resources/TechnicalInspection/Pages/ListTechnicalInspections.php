<?php

declare(strict_types=1);

namespace App\Filament\Resources\TechnicalInspection\Pages;

use App\Filament\Resources\TechnicalInspection\TechnicalInspectionResource;
use Filament\Resources\Pages\ListRecords;

final class ListTechnicalInspections extends ListRecords
{
    protected static string $resource = TechnicalInspectionResource::class;

    public function mount(): void
    {
        parent::mount();

        $this->redirect(TechnicalInspectionResource::getUrl('edit', [
            'record' => TechnicalInspectionResource::hub(),
        ]));
    }
}
