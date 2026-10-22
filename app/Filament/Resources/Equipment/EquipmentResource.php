<?php

declare(strict_types=1);

namespace App\Filament\Resources\Equipment;

use App\Enums\EquipmentLifecycle;
use App\Enums\Locale;
use App\Filament\Clusters\TechnicalInspection\TechnicalInspectionCluster;
use App\Filament\Resources\Equipment\Pages\CreateEquipment;
use App\Filament\Resources\Equipment\Pages\EditEquipment;
use App\Filament\Resources\Equipment\Pages\ListEquipment;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\LocaleTabs;
use App\Models\Equipment;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class EquipmentResource extends Resource
{
    use AuthorizesCmsResource;

    protected static ?string $model = Equipment::class;

    protected static ?string $cluster = TechnicalInspectionCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static ?string $navigationLabel = 'Équipements';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'equipment';

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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identité')
                ->description('En service ou historique. Ne pas supprimer un modèle remplacé : le passer en historique.')
                ->schema([
                    Select::make('lifecycle')
                        ->label('Cycle de vie')
                        ->options([
                            EquipmentLifecycle::Current->value => 'En service',
                            EquipmentLifecycle::Legacy->value => 'Historique',
                        ])
                        ->required(),
                    TextInput::make('sort_order')->numeric()->default(0)->label('Ordre'),
                    TextInput::make('publication_status')->disabled()->visibleOn('edit'),
                ]),
            Section::make('Photo')
                ->description('Optionnelle. Un visuel exige un alt FR et un alt EN.')
                ->visibleOn('edit')
                ->schema([
                    FileUpload::make('photo')
                        ->image()
                        ->storeFiles(false)
                        ->maxFiles(1),
                    TextInput::make('photo_alt_fr')->label('Alt FR'),
                    TextInput::make('photo_alt_en')->label('Alt EN'),
                ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.slug")->label('Slug'),
                TextInput::make("translations.{$locale}.title")
                    ->required($locale === Locale::French->value)
                    ->label('Titre'),
                Textarea::make("translations.{$locale}.body")->label('Corps')->rows(6),
                TextInput::make("translations.{$locale}.meta_title"),
                Textarea::make("translations.{$locale}.meta_description"),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('title')
                    ->label('Titre')
                    ->getStateUsing(function (Equipment $record): string {
                        $translation = $record->translationFor('fr');

                        return is_object($translation) && isset($translation->title)
                            ? (string) $translation->title
                            : '—';
                    }),
                TextColumn::make('lifecycle')
                    ->badge()
                    ->formatStateUsing(function (EquipmentLifecycle|string|null $state): string {
                        $lifecycle = $state instanceof EquipmentLifecycle
                            ? $state
                            : EquipmentLifecycle::tryFrom((string) $state);

                        return $lifecycle === EquipmentLifecycle::Current ? 'En service' : 'Historique';
                    }),
                TextColumn::make('publication_status')->badge(),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with('translations')
                ->orderByRaw("CASE lifecycle WHEN 'current' THEN 0 ELSE 1 END")
                ->orderBy('sort_order'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEquipment::route('/'),
            'create' => CreateEquipment::route('/create'),
            'edit' => EditEquipment::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'équipement';
    }

    public static function getPluralModelLabel(): string
    {
        return 'équipements';
    }
}
