<?php

declare(strict_types=1);

namespace App\Filament\Resources\Expertises;

use App\Filament\Resources\Expertises\Pages\EditExpertise;
use App\Filament\Resources\Expertises\Pages\ListExpertises;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\ForbidsCreateAndDelete;
use App\Filament\Support\LocaleTabs;
use App\Models\Expertise;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class ExpertiseResource extends Resource
{
    use AuthorizesCmsResource;
    use ForbidsCreateAndDelete;

    protected static ?string $model = Expertise::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|\UnitEnum|null $navigationGroup = 'Expertise';

    protected static ?string $navigationLabel = 'Expertises';

    protected static ?int $navigationSort = 30;

    protected static ?string $slug = 'expertises';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identité')
                ->description('Cinq domaines figés. Les titres ne peuvent pas recopier un nom de produit.')
                ->schema([
                    TextInput::make('code')->disabled(),
                    TextInput::make('sort_order')->disabled()->label('Ordre sitemap'),
                    TextInput::make('icon')
                        ->label('Icône')
                        ->helperText('Clé Lucide optionnelle. Pas un logo produit.')
                        ->maxLength(64),
                    TextInput::make('publication_status')->disabled(),
                ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.slug")->disabled()->label('Slug'),
                TextInput::make("translations.{$locale}.title")->required()->label('Titre'),
                Textarea::make("translations.{$locale}.summary")->label('Résumé'),
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
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('code')->badge(),
                TextColumn::make('title')
                    ->label('Titre')
                    ->getStateUsing(function (Expertise $record): string {
                        $translation = $record->translationFor('fr');

                        return is_object($translation) && isset($translation->title)
                            ? (string) $translation->title
                            : $record->code->value;
                    }),
                TextColumn::make('publication_status')->badge(),
            ])
            ->defaultSort('sort_order')
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpertises::route('/'),
            'edit' => EditExpertise::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'expertise';
    }

    public static function getPluralModelLabel(): string
    {
        return 'expertises';
    }
}
