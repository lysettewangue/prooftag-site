<?php

declare(strict_types=1);

namespace App\Filament\Resources\Partners;

use App\Filament\Resources\Partners\Pages\CreatePartner;
use App\Filament\Resources\Partners\Pages\EditPartner;
use App\Filament\Resources\Partners\Pages\ListPartners;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\LocaleTabs;
use App\Models\Partner;
use Filament\Forms\Components\FileUpload;
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

final class PartnerResource extends Resource
{
    use AuthorizesCmsResource;

    protected static ?string $model = Partner::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Corporate';

    protected static ?string $navigationLabel = 'Partenaires';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'partners';

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
                ->description('VER-09 est ouvert : ne pas publier MINT, Prooftag SAS ou d’autres partenaires avant vérification des logos et URL. Archiver plutôt que supprimer.')
                ->schema([
                    TextInput::make('name')->required()->label('Nom'),
                    TextInput::make('sort_order')->numeric()->default(0)->label('Ordre'),
                    TextInput::make('url')->url()->maxLength(255)->label('Site'),
                    TextInput::make('publication_status')->disabled()->visibleOn('edit')->label('Publication'),
                ]),
            Section::make('Logo')
                ->description('Optionnel jusqu’à VER-09. Un visuel exige un alt FR et un alt EN.')
                ->visibleOn('edit')
                ->schema([
                    FileUpload::make('logo')
                        ->image()
                        ->storeFiles(false)
                        ->maxFiles(1),
                    TextInput::make('logo_alt_fr')->label('Alt FR'),
                    TextInput::make('logo_alt_en')->label('Alt EN'),
                ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.slug")->label('Slug'),
                Textarea::make("translations.{$locale}.blurb")->label('Présentation')->rows(4),
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
                TextColumn::make('name')->label('Nom')->searchable()->sortable(),
                TextColumn::make('url')
                    ->label('Site')
                    ->placeholder('—')
                    ->limit(40),
                TextColumn::make('publication_status')->badge()->label('Publication'),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with('translations')
                ->orderBy('sort_order')
                ->orderBy('name'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartners::route('/'),
            'create' => CreatePartner::route('/create'),
            'edit' => EditPartner::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'partenaire';
    }

    public static function getPluralModelLabel(): string
    {
        return 'partenaires';
    }
}
