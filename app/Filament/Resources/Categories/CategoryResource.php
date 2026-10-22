<?php

declare(strict_types=1);

namespace App\Filament\Resources\Categories;

use App\Enums\Locale;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\LocaleTabs;
use App\Models\Category;
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

final class CategoryResource extends Resource
{
    use AuthorizesCmsResource;

    protected static ?string $model = Category::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|\UnitEnum|null $navigationGroup = 'Éditorial';

    protected static ?string $navigationLabel = 'Catégories';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'categories';

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
                ->description('Une catégorie non publiée n’apparaît pas dans les filtres publics. Archiver plutôt que supprimer.')
                ->schema([
                    TextInput::make('sort_order')->numeric()->default(0)->label('Ordre'),
                    TextInput::make('publication_status')->disabled()->visibleOn('edit')->label('Publication'),
                ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.slug")->label('Slug'),
                TextInput::make("translations.{$locale}.name")
                    ->required($locale === Locale::French->value)
                    ->label('Nom'),
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
                TextColumn::make('name')
                    ->label('Nom')
                    ->getStateUsing(function (Category $record): string {
                        $translation = $record->translationFor('fr');

                        return is_object($translation) && isset($translation->name)
                            ? (string) $translation->name
                            : '—';
                    }),
                TextColumn::make('publication_status')->badge()->label('Publication'),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with('translations')
                ->orderBy('sort_order')
                ->orderBy('id'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'catégorie';
    }

    public static function getPluralModelLabel(): string
    {
        return 'catégories';
    }
}
