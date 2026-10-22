<?php

declare(strict_types=1);

namespace App\Filament\Resources\Redirects;

use App\Filament\Resources\Redirects\Pages\CreateRedirect;
use App\Filament\Resources\Redirects\Pages\EditRedirect;
use App\Filament\Resources\Redirects\Pages\ListRedirects;
use App\Models\Redirect;
use Filament\Forms\Components\Select;
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

final class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static string|\UnitEnum|null $navigationGroup = 'Corporate';

    protected static ?string $navigationLabel = 'Redirections';

    protected static ?int $navigationSort = 40;

    protected static ?string $slug = 'redirects';

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
        /** @var list<string> $hosts */
        $hosts = config('cms.allowed_redirect_hosts', []);

        return $schema->components([
            Section::make('Redirection')
                ->description('A-14. Les métas SEO vivent sur Paramètres et chaque fiche, pas ici. Hôte canonique www.prooftagcatis.com. DITROS-CT attend l’étape 166 : garder is_active faux si besoin. Unique (hôte, chemin). Désactiver plutôt que supprimer.')
                ->schema([
                    Select::make('from_host')
                        ->label('Hôte source')
                        ->options(array_combine($hosts, $hosts))
                        ->required(),
                    TextInput::make('from_path')->required()->startsWith('/')->maxLength(2048)->label('Chemin source'),
                    TextInput::make('to_path')->required()->startsWith('/')->maxLength(2048)->label('Chemin cible'),
                    Select::make('status_code')
                        ->label('Code')
                        ->options([
                            301 => '301',
                            302 => '302',
                        ])
                        ->default(301)
                        ->required(),
                    Toggle::make('is_active')->default(true)->label('Active'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('from_host')->label('Hôte')->searchable(),
                TextColumn::make('from_path')->label('Depuis')->searchable(),
                TextColumn::make('to_path')->label('Vers'),
                TextColumn::make('status_code')->label('Code')->badge(),
                IconColumn::make('is_active')->boolean()->label('Active'),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->latest('id'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRedirects::route('/'),
            'create' => CreateRedirect::route('/create'),
            'edit' => EditRedirect::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'redirection';
    }

    public static function getPluralModelLabel(): string
    {
        return 'redirections';
    }
}
