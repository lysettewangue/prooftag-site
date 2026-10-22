<?php

declare(strict_types=1);

namespace App\Filament\Resources\LibraryDocuments;

use App\Enums\Locale;
use App\Enums\ResourceRegulatoryStatus;
use App\Filament\Resources\LibraryDocuments\Pages\CreateLibraryDocument;
use App\Filament\Resources\LibraryDocuments\Pages\EditLibraryDocument;
use App\Filament\Resources\LibraryDocuments\Pages\ListLibraryDocuments;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\LocaleTabs;
use App\Models\Product;
use App\Models\Resource as CmsResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class LibraryDocumentResource extends Resource
{
    use AuthorizesCmsResource;

    protected static ?string $model = CmsResource::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|\UnitEnum|null $navigationGroup = 'Ressources';

    protected static ?string $navigationLabel = 'Documents';

    protected static ?int $navigationSort = 60;

    protected static ?string $slug = 'resources';

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
            Section::make('Identité')
                ->description('VER-08 est ouvert : ne pas publier un texte 2020 comme « en vigueur » s’il est historique. Archive est un libellé, pas une suppression. Un PDF est requis avant publication.')
                ->schema([
                    Select::make('regulatory_status')
                        ->label('Statut réglementaire')
                        ->options([
                            ResourceRegulatoryStatus::Current->value => 'En vigueur',
                            ResourceRegulatoryStatus::Historical->value => 'Historique',
                            ResourceRegulatoryStatus::Archive->value => 'Archive',
                        ])
                        ->required(),
                    TextInput::make('authority')->label('Autorité'),
                    DatePicker::make('issued_on')->label('Date du document'),
                    Toggle::make('relates_to_ct')->label('Lié au contrôle technique')->default(false),
                    Select::make('product_ids')
                        ->label('Produits')
                        ->multiple()
                        ->options(fn (): array => Product::query()->orderBy('sort_order')->pluck('public_name', 'id')->all()),
                    TextInput::make('publication_status')->disabled()->visibleOn('edit')->label('Publication'),
                ]),
            Section::make('Fichier')
                ->description('PDF sur le disque documents. Téléchargement public uniquement via la route contrôlée.')
                ->schema([
                    FileUpload::make('document')
                        ->acceptedFileTypes(['application/pdf'])
                        ->storeFiles(false)
                        ->maxFiles(1)
                        ->maxSize(8192),
                ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.slug")->label('Slug'),
                TextInput::make("translations.{$locale}.title")
                    ->required($locale === Locale::French->value)
                    ->label('Titre'),
                Textarea::make("translations.{$locale}.summary")->label('Résumé')->rows(4),
                TextInput::make("translations.{$locale}.meta_title"),
                Textarea::make("translations.{$locale}.meta_description"),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titre')
                    ->getStateUsing(function (CmsResource $record): string {
                        $translation = $record->translationFor('fr');

                        return is_object($translation) && isset($translation->title)
                            ? (string) $translation->title
                            : '—';
                    }),
                TextColumn::make('regulatory_status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(function (ResourceRegulatoryStatus|string|null $state): string {
                        $status = $state instanceof ResourceRegulatoryStatus
                            ? $state
                            : ResourceRegulatoryStatus::tryFrom((string) $state);

                        return match ($status) {
                            ResourceRegulatoryStatus::Current => 'En vigueur',
                            ResourceRegulatoryStatus::Historical => 'Historique',
                            ResourceRegulatoryStatus::Archive => 'Archive',
                            default => '—',
                        };
                    }),
                IconColumn::make('relates_to_ct')->boolean()->label('CT'),
                TextColumn::make('publication_status')->badge()->label('Publication'),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with('translations')
                ->latest('id'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLibraryDocuments::route('/'),
            'create' => CreateLibraryDocument::route('/create'),
            'edit' => EditLibraryDocument::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'document';
    }

    public static function getPluralModelLabel(): string
    {
        return 'documents';
    }
}
