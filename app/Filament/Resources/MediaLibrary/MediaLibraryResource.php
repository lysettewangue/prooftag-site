<?php

declare(strict_types=1);

namespace App\Filament\Resources\MediaLibrary;

use App\Filament\Resources\MediaLibrary\Pages\EditMediaLibraryItem;
use App\Filament\Resources\MediaLibrary\Pages\ListMediaLibrary;
use App\Filament\Support\AuthorizesCmsResource;
use App\Models\Media;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class MediaLibraryResource extends Resource
{
    use AuthorizesCmsResource;

    protected static ?string $model = Media::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|\UnitEnum|null $navigationGroup = 'Ressources';

    protected static ?string $navigationLabel = 'Médiathèque';

    protected static ?int $navigationSort = 70;

    protected static ?string $slug = 'media';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        return $user instanceof User && $user->can('delete', $record);
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

    public static function canRestore(Model $record): bool
    {
        return false;
    }

    public static function canRestoreAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Fichier')
                ->description('Whitelist MIME jpeg/png/webp/svg/pdf, 8 Mo. Les fichiers s’attachent sur leur propriétaire, pas ici. Alt FR et EN obligatoires pour une image. Droits vides = ne pas utiliser en public. Suppression bloquée tant que le propriétaire est public. Téléchargement public des documents : DownloadResource (plus tard).')
                ->schema([
                    TextInput::make('file_name')->disabled()->label('Fichier'),
                    TextInput::make('mime_type')->disabled()->label('MIME'),
                    TextInput::make('collection_name')->disabled()->label('Collection'),
                    TextInput::make('disk')->disabled()->label('Disque'),
                    TextInput::make('size')->disabled()->label('Taille (octets)'),
                    TextInput::make('model_type')->disabled()->label('Propriétaire'),
                    TextInput::make('model_id')->disabled()->label('Id propriétaire'),
                    TextInput::make('rights')->maxLength(255)->label('Droits / provenance'),
                    TextInput::make('alt_fr')->maxLength(255)->label('Alt FR'),
                    TextInput::make('alt_en')->maxLength(255)->label('Alt EN'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('file_name')->label('Fichier')->searchable(),
                TextColumn::make('mime_type')->label('MIME')->badge(),
                TextColumn::make('collection_name')->label('Collection'),
                TextColumn::make('disk')->label('Disque'),
                TextColumn::make('owner')
                    ->label('Propriétaire')
                    ->getStateUsing(function (Media $record): string {
                        $type = class_basename((string) $record->model_type);

                        return $type !== '' ? $type.' #'.$record->model_id : '—';
                    }),
                TextColumn::make('rights')
                    ->label('Droits')
                    ->getStateUsing(fn (Media $record): string => $record->rights() !== '' ? $record->rights() : '—'),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->latest('id'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMediaLibrary::route('/'),
            'edit' => EditMediaLibraryItem::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'média';
    }

    public static function getPluralModelLabel(): string
    {
        return 'médias';
    }
}
