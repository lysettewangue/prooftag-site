<?php

declare(strict_types=1);

namespace App\Filament\Resources\SitePages\Pages;

use App\Filament\Resources\SitePages\SitePageResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

final class ListSitePages extends ListRecords
{
    protected static string $resource = SitePageResource::class;

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return [];
    }
}
