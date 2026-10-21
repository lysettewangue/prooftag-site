<?php

declare(strict_types=1);

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\Pages\EditSiteSetting;
use App\Filament\Resources\SiteSettings\Pages\ListSiteSettings;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\ForbidsCreateAndDelete;
use App\Filament\Support\LocaleTabs;
use App\Models\SiteSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class SiteSettingResource extends Resource
{
    use AuthorizesCmsResource;
    use ForbidsCreateAndDelete;

    protected static ?string $model = SiteSetting::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|\UnitEnum|null $navigationGroup = 'Corporate';

    protected static ?string $navigationLabel = 'Paramètres';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'settings';

    public static function getNavigationUrl(): string
    {
        return self::getUrl('edit', ['record' => 1]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identité (figée)')
                ->description('Raison sociale, adresse et téléphone : nouveau millésime du contact master record.')
                ->schema([
                    TextInput::make('legal_name')->disabled(),
                    TextInput::make('brand')->disabled(),
                    TextInput::make('building')->disabled(),
                    TextInput::make('city')->disabled(),
                    TextInput::make('country')->disabled(),
                    TextInput::make('postal_box')->disabled(),
                    TextInput::make('telephone')->disabled(),
                    TextInput::make('email')->disabled(),
                    TextInput::make('canonical_url')->disabled(),
                ]),
            Section::make('Logo et médias')->schema([
                FileUpload::make('logo')
                    ->image()
                    ->storeFiles(false)
                    ->maxFiles(1),
                TextInput::make('logo_alt_fr')->label('Alt FR'),
                TextInput::make('logo_alt_en')->label('Alt EN'),
            ]),
            Section::make('Réseaux et géolocalisation (VER-10)')->schema([
                TextInput::make('social_linkedin')->url(),
                TextInput::make('social_facebook')->url(),
                TextInput::make('social_x')->url(),
                TextInput::make('social_youtube')->url(),
                TextInput::make('social_instagram')->url(),
                TextInput::make('geo_latitude'),
                TextInput::make('geo_longitude'),
            ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.tagline"),
                TextInput::make("translations.{$locale}.meta_title"),
                TextInput::make("translations.{$locale}.meta_description"),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('legal_name'),
            TextColumn::make('email'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteSettings::route('/'),
            'edit' => EditSiteSetting::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'paramètres';
    }
}
