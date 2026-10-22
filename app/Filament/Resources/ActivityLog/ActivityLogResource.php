<?php

declare(strict_types=1);

namespace App\Filament\Resources\ActivityLog;

use App\Filament\Resources\ActivityLog\Pages\ListActivityLog;
use App\Filament\Resources\ActivityLog\Pages\ViewActivityLog;
use App\Models\Activity;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Journal';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'audit';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canForceDelete(Model $record): bool
    {
        return false;
    }

    public static function canForceDeleteAny(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Événement')
                ->description('Lecture seule. Mots de passe jamais enregistrés. Journaux auth et staff.')
                ->schema([
                    TextEntry::make('log_name')->label('Journal'),
                    TextEntry::make('event')->label('Événement'),
                    TextEntry::make('description')->label('Description'),
                    TextEntry::make('causer.email')->label('Auteur')->placeholder('—'),
                    TextEntry::make('subject_type')->label('Sujet'),
                    TextEntry::make('subject_id')->label('Id sujet'),
                    TextEntry::make('created_at')->dateTime()->label('Quand'),
                    TextEntry::make('properties')
                        ->label('Propriétés')
                        ->formatStateUsing(function (mixed $state): string {
                            $encoded = json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                            return is_string($encoded) ? $encoded : '—';
                        }),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->dateTime()->label('Quand')->sortable(),
                TextColumn::make('log_name')->label('Journal')->badge(),
                TextColumn::make('event')->label('Événement'),
                TextColumn::make('causer.email')->label('Auteur')->placeholder('—'),
                TextColumn::make('subject_type')
                    ->label('Sujet')
                    ->formatStateUsing(fn (?string $state): string => $state !== null && $state !== '' ? class_basename($state) : '—'),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label('Journal')
                    ->options([
                        'auth' => 'auth',
                        'staff' => 'staff',
                    ]),
            ])
            ->recordUrl(fn (Model $record): string => self::getUrl('view', ['record' => $record]))
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with('causer')
                ->latest('id'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLog::route('/'),
            'view' => ViewActivityLog::route('/{record}'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'événement';
    }

    public static function getPluralModelLabel(): string
    {
        return 'événements';
    }
}
