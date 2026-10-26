<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

final class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Vue d’ensemble';

    protected static ?string $navigationLabel = 'Vue d’ensemble';

    public function getSubheading(): string
    {
        $name = auth()->user()?->name;

        return filled($name)
            ? "Bonjour {$name}. Voici les priorités de la plateforme."
            : 'Voici les priorités de la plateforme.';
    }
}
