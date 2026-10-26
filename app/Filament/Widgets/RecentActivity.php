<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Resources\ActivityLog\ActivityLogResource;
use App\Models\Activity;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Gate;

final class RecentActivity extends TableWidget
{
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        $user = auth()->user();

        return $user instanceof User && Gate::forUser($user)->allows('viewAny', Activity::class);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Activité récente')
            ->description('Dernières opérations sensibles enregistrées sur la plateforme.')
            ->query(Activity::query()->with('causer')->latest('id')->limit(6))
            ->columns([
                TextColumn::make('event')
                    ->label('Action')
                    ->badge()
                    ->color('info'),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(42),
                TextColumn::make('causer.email')
                    ->label('Auteur')
                    ->placeholder('Système'),
                TextColumn::make('created_at')
                    ->label('Quand')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->recordUrl(fn (Activity $record): string => ActivityLogResource::getUrl('view', ['record' => $record]))
            ->paginated(false);
    }
}
