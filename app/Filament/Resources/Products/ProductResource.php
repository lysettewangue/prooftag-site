<?php

declare(strict_types=1);

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\ForbidsCreateAndDelete;
use App\Filament\Support\LocaleTabs;
use App\Models\Expertise;
use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class ProductResource extends Resource
{
    use AuthorizesCmsResource;
    use ForbidsCreateAndDelete;

    protected static ?string $model = Product::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static string|\UnitEnum|null $navigationGroup = 'Solutions';

    protected static ?string $navigationLabel = 'Produits';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'products';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identité')
                ->description('Cinq solutions figées. CT-VERIF et DOSER exigent une URL externe avant publication.')
                ->schema([
                    TextInput::make('code')->disabled(),
                    TextInput::make('public_name')->disabled(),
                    TextInput::make('slug_fragment')->disabled()->label('Slug figé'),
                    TextInput::make('external_url')->url()->maxLength(2048),
                    Select::make('expertise_ids')
                        ->label('Expertises')
                        ->multiple()
                        ->options(fn (): array => Expertise::query()->with('translations')->get()
                            ->mapWithKeys(function (Expertise $expertise): array {
                                $translation = $expertise->translationFor('fr');
                                $title = is_object($translation) && isset($translation->title)
                                    ? (string) $translation->title
                                    : $expertise->code->value;

                                return [$expertise->id => $title];
                            })
                            ->all()),
                    TextInput::make('publication_status')->disabled(),
                ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.slug")->disabled()->label('Slug'),
                Textarea::make("translations.{$locale}.summary")->label('Résumé'),
                Textarea::make("translations.{$locale}.body")->label('Corps')->rows(8),
                TextInput::make("translations.{$locale}.external_label"),
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
                TextColumn::make('public_name')->searchable(),
                TextColumn::make('publication_status')->badge(),
            ])
            ->defaultSort('sort_order')
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'produit';
    }

    public static function getPluralModelLabel(): string
    {
        return 'produits';
    }
}
