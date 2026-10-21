<?php

declare(strict_types=1);

namespace App\Filament\Support;

use App\Enums\Locale;
use Closure;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

final class LocaleTabs
{
    /**
     * @param  Closure(string): array<int, mixed>  $schema
     */
    public static function make(Closure $schema): Tabs
    {
        return Tabs::make('translations')
            ->tabs([
                Tab::make('Français')->schema($schema(Locale::French->value)),
                Tab::make('English')->schema($schema(Locale::English->value)),
            ]);
    }
}
