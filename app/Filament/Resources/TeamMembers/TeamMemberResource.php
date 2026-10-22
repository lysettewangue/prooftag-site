<?php

declare(strict_types=1);

namespace App\Filament\Resources\TeamMembers;

use App\Enums\Locale;
use App\Filament\Resources\TeamMembers\Pages\CreateTeamMember;
use App\Filament\Resources\TeamMembers\Pages\EditTeamMember;
use App\Filament\Resources\TeamMembers\Pages\ListTeamMembers;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\LocaleTabs;
use App\Models\TeamMember;
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

final class TeamMemberResource extends Resource
{
    use AuthorizesCmsResource;

    protected static ?string $model = TeamMember::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|\UnitEnum|null $navigationGroup = 'Corporate';

    protected static ?string $navigationLabel = 'Équipe';

    protected static ?int $navigationSort = 10;

    protected static ?string $slug = 'team-members';

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
                ->description('VER-02 est ouvert : ne pas publier un organigramme mixte (officiers du site vs www). Archiver plutôt que supprimer.')
                ->schema([
                    TextInput::make('full_name')->required()->label('Nom'),
                    TextInput::make('sort_order')->numeric()->default(0)->label('Ordre'),
                    TextInput::make('publication_status')->disabled()->visibleOn('edit')->label('Publication'),
                ]),
            Section::make('Photo')
                ->description('Optionnelle jusqu’à VER-02. Un visuel exige un alt FR et un alt EN.')
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
                    ->label('Fonction'),
                Textarea::make("translations.{$locale}.bio")->label('Biographie')->rows(5),
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
                TextColumn::make('full_name')->label('Nom')->searchable()->sortable(),
                TextColumn::make('title')
                    ->label('Fonction')
                    ->getStateUsing(function (TeamMember $record): string {
                        $translation = $record->translationFor('fr');

                        return is_object($translation) && isset($translation->title)
                            ? (string) $translation->title
                            : '—';
                    }),
                TextColumn::make('publication_status')->badge()->label('Publication'),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with('translations')
                ->orderBy('sort_order')
                ->orderBy('full_name'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeamMembers::route('/'),
            'create' => CreateTeamMember::route('/create'),
            'edit' => EditTeamMember::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'membre';
    }

    public static function getPluralModelLabel(): string
    {
        return 'membres';
    }
}
