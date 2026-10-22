<?php

declare(strict_types=1);

namespace App\Filament\Resources\Careers;

use App\Enums\CareerKind;
use App\Enums\Locale;
use App\Filament\Resources\Careers\Pages\CreateCareer;
use App\Filament\Resources\Careers\Pages\EditCareer;
use App\Filament\Resources\Careers\Pages\ListCareers;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\LocaleTabs;
use App\Models\Career;
use Filament\Forms\Components\DateTimePicker;
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

final class CareerResource extends Resource
{
    use AuthorizesCmsResource;

    protected static ?string $model = Career::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static string|\UnitEnum|null $navigationGroup = 'Corporate';

    protected static ?string $navigationLabel = 'Carrières';

    protected static ?int $navigationSort = 30;

    protected static ?string $slug = 'careers';

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
                ->description('Une liste vide est un état de lancement valide (P-11). Ce n’est pas un Post. URL de candidature optionnelle : vide = formulaire de contact. Les blocs au-delà du texte riche attendent l’étape 105. Archiver plutôt que supprimer.')
                ->schema([
                    Select::make('kind')
                        ->label('Type')
                        ->options([
                            CareerKind::Job->value => 'Emploi',
                            CareerKind::Internship->value => 'Stage',
                        ])
                        ->required(),
                    DateTimePicker::make('closes_at')->label('Clôture'),
                    TextInput::make('application_url')->url()->maxLength(255)->label('URL de candidature'),
                    TextInput::make('publication_status')->disabled()->visibleOn('edit')->label('Publication'),
                ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.slug")->label('Slug'),
                TextInput::make("translations.{$locale}.title")
                    ->required($locale === Locale::French->value)
                    ->label('Titre'),
                Textarea::make("translations.{$locale}.body")->label('Corps')->rows(8),
                TextInput::make("translations.{$locale}.meta_title"),
                Textarea::make("translations.{$locale}.meta_description"),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kind')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(function (CareerKind|string|null $state): string {
                        $kind = $state instanceof CareerKind ? $state : CareerKind::tryFrom((string) $state);

                        return $kind === CareerKind::Internship ? 'Stage' : 'Emploi';
                    }),
                TextColumn::make('title')
                    ->label('Titre')
                    ->getStateUsing(function (Career $record): string {
                        $translation = $record->translationFor('fr');

                        return is_object($translation) && isset($translation->title)
                            ? (string) $translation->title
                            : '—';
                    }),
                TextColumn::make('closes_at')->dateTime()->label('Clôture')->placeholder('—'),
                TextColumn::make('publication_status')->badge()->label('Publication'),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with('translations')
                ->latest('id'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCareers::route('/'),
            'create' => CreateCareer::route('/create'),
            'edit' => EditCareer::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'offre';
    }

    public static function getPluralModelLabel(): string
    {
        return 'offres';
    }
}
