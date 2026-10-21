<?php

declare(strict_types=1);

namespace App\Filament\Resources\Expertises\Pages;

use App\Filament\Resources\Expertises\ExpertiseResource;
use Filament\Resources\Pages\ListRecords;

final class ListExpertises extends ListRecords
{
    protected static string $resource = ExpertiseResource::class;
}
