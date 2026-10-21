<?php

declare(strict_types=1);

namespace App\Filament\Clusters\TechnicalInspection;

use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

final class TechnicalInspectionCluster extends Cluster
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?string $navigationLabel = 'Contrôle technique';

    protected static ?string $clusterBreadcrumb = 'Contrôle technique';

    protected static ?int $navigationSort = 40;

    protected static ?string $slug = 'technical-inspection';
}
