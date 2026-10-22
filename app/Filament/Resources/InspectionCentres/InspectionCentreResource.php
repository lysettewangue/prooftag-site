<?php

declare(strict_types=1);

namespace App\Filament\Resources\InspectionCentres;

use App\Enums\CentreVerificationStatus;
use App\Enums\Locale;
use App\Filament\Resources\InspectionCentres\Pages\CreateInspectionCentre;
use App\Filament\Resources\InspectionCentres\Pages\EditInspectionCentre;
use App\Filament\Resources\InspectionCentres\Pages\ListInspectionCentres;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\LocaleTabs;
use App\Models\InspectionCentre;
use App\Models\Region;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class InspectionCentreResource extends Resource
{
    use AuthorizesCmsResource;

    protected static ?string $model = InspectionCentre::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|\UnitEnum|null $navigationGroup = 'Centres';

    protected static ?string $navigationLabel = 'Annuaire';

    protected static ?int $navigationSort = 50;

    protected static ?string $slug = 'centres';

    public static function canEdit(Model $record): bool
    {
        return self::can('update', $record)
            || self::can('translate', $record)
            || self::can('verify', $record);
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
                ->description('Région obligatoire parmi les dix régions du Cameroun. L’import reste non vérifié. La liste publique n’affiche que les centres actifs.')
                ->schema([
                    Select::make('region_id')
                        ->label('Région')
                        ->options(fn (): array => self::regionOptions())
                        ->required()
                        ->searchable(),
                    TextInput::make('operator_name')->required()->label('Exploitant'),
                    TextInput::make('city')->required()->label('Ville'),
                    TextInput::make('phone')->label('Téléphone'),
                    TextInput::make('email')->email()->label('E-mail'),
                    TextInput::make('latitude')->numeric()->label('Latitude'),
                    TextInput::make('longitude')->numeric()->label('Longitude'),
                    TextInput::make('sort_order')->numeric()->label('Ordre'),
                    TextInput::make('verification_status')->disabled()->visibleOn('edit')->label('Vérification'),
                    TextInput::make('last_verified_at')->disabled()->visibleOn('edit')->label('Dernière vérification'),
                ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.slug")->label('Slug'),
                TextInput::make("translations.{$locale}.name")
                    ->required($locale === Locale::French->value)
                    ->label('Nom public'),
                TextInput::make("translations.{$locale}.address_line")->label('Adresse'),
                Textarea::make("translations.{$locale}.body")->label('Présentation')->rows(4),
                TextInput::make("translations.{$locale}.meta_title"),
                Textarea::make("translations.{$locale}.meta_description"),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('city')->label('Ville')->searchable()->sortable(),
                TextColumn::make('name')
                    ->label('Nom')
                    ->getStateUsing(function (InspectionCentre $record): string {
                        $translation = $record->translationFor('fr');

                        return is_object($translation) && isset($translation->name)
                            ? (string) $translation->name
                            : (string) $record->operator_name;
                    }),
                TextColumn::make('region')
                    ->label('Région')
                    ->getStateUsing(function (InspectionCentre $record): string {
                        $region = $record->region;
                        $translation = $region instanceof Region ? $region->translationFor('fr') : null;

                        return is_object($translation) && isset($translation->name)
                            ? (string) $translation->name
                            : '—';
                    }),
                TextColumn::make('verification_status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(function (CentreVerificationStatus|string|null $state): string {
                        $status = $state instanceof CentreVerificationStatus
                            ? $state
                            : CentreVerificationStatus::tryFrom((string) $state);

                        return match ($status) {
                            CentreVerificationStatus::Active => 'Actif',
                            CentreVerificationStatus::TemporarilyUnavailable => 'Indisponible',
                            CentreVerificationStatus::Archived => 'Archivé',
                            default => 'Non vérifié',
                        };
                    }),
            ])
            ->filters([
                SelectFilter::make('region_id')
                    ->label('Région')
                    ->options(fn (): array => self::regionOptions()),
                SelectFilter::make('verification_status')
                    ->label('Statut')
                    ->options([
                        CentreVerificationStatus::Unverified->value => 'Non vérifié',
                        CentreVerificationStatus::Active->value => 'Actif',
                        CentreVerificationStatus::TemporarilyUnavailable->value => 'Indisponible',
                        CentreVerificationStatus::Archived->value => 'Archivé',
                    ]),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with(['translations', 'region.translations'])
                ->orderBy('city')
                ->orderBy('sort_order'));
    }

    /**
     * @return array<int, string>
     */
    public static function regionOptions(): array
    {
        return Region::query()
            ->with('translations')
            ->orderBy('id')
            ->get()
            ->mapWithKeys(function (Region $region): array {
                $translation = $region->translationFor('fr');
                $label = is_object($translation) && isset($translation->name)
                    ? (string) $translation->name
                    : (string) $region->code->value;

                return [$region->id => $label];
            })
            ->all();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInspectionCentres::route('/'),
            'create' => CreateInspectionCentre::route('/create'),
            'edit' => EditInspectionCentre::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'centre';
    }

    public static function getPluralModelLabel(): string
    {
        return 'centres';
    }
}
