<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts;

use App\Enums\Locale;
use App\Enums\PostKind;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\LocaleTabs;
use App\Models\Category;
use App\Models\Expertise;
use App\Models\Post;
use App\Models\Product;
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

final class PostResource extends Resource
{
    use AuthorizesCmsResource;

    protected static ?string $model = Post::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|\UnitEnum|null $navigationGroup = 'Éditorial';

    protected static ?string $navigationLabel = 'Publications';

    protected static ?int $navigationSort = 10;

    protected static ?string $slug = 'posts';

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
                ->description('Les brouillons ne sont jamais publics. Archiver plutôt que supprimer. Les blocs au-delà du texte riche attendent l’étape 105.')
                ->schema([
                    Select::make('kind')
                        ->label('Type')
                        ->options([
                            PostKind::News->value => 'Actualité',
                            PostKind::Article->value => 'Article',
                        ])
                        ->required(),
                    Select::make('category_ids')
                        ->label('Catégories')
                        ->multiple()
                        ->options(fn (): array => Category::query()->with('translations')->orderBy('sort_order')->get()
                            ->mapWithKeys(function (Category $category): array {
                                $translation = $category->translationFor('fr');
                                $name = is_object($translation) && isset($translation->name)
                                    ? (string) $translation->name
                                    : '#'.$category->id;

                                return [$category->id => $name];
                            })
                            ->all()),
                    Select::make('product_ids')
                        ->label('Produits')
                        ->multiple()
                        ->options(fn (): array => Product::query()->orderBy('sort_order')->pluck('public_name', 'id')->all()),
                    Select::make('expertise_ids')
                        ->label('Expertises')
                        ->multiple()
                        ->options(fn (): array => Expertise::query()->with('translations')->orderBy('sort_order')->get()
                            ->mapWithKeys(function (Expertise $expertise): array {
                                $translation = $expertise->translationFor('fr');
                                $title = is_object($translation) && isset($translation->title)
                                    ? (string) $translation->title
                                    : $expertise->code->value;

                                return [$expertise->id => $title];
                            })
                            ->all()),
                    TextInput::make('publication_status')->disabled()->visibleOn('edit')->label('Publication'),
                ]),
            Section::make('Image')
                ->description('Optionnelle. Un visuel exige un alt FR et un alt EN.')
                ->visibleOn('edit')
                ->schema([
                    FileUpload::make('hero')
                        ->image()
                        ->storeFiles(false)
                        ->maxFiles(1),
                    TextInput::make('hero_alt_fr')->label('Alt FR'),
                    TextInput::make('hero_alt_en')->label('Alt EN'),
                ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.slug")->label('Slug'),
                TextInput::make("translations.{$locale}.title")
                    ->required($locale === Locale::French->value)
                    ->label('Titre'),
                Textarea::make("translations.{$locale}.summary")->label('Chapô')->rows(3),
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
                    ->formatStateUsing(function (PostKind|string|null $state): string {
                        $kind = $state instanceof PostKind ? $state : PostKind::tryFrom((string) $state);

                        return $kind === PostKind::Article ? 'Article' : 'Actualité';
                    }),
                TextColumn::make('title')
                    ->label('Titre')
                    ->getStateUsing(function (Post $record): string {
                        $translation = $record->translationFor('fr');

                        return is_object($translation) && isset($translation->title)
                            ? (string) $translation->title
                            : '—';
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('translations', function (Builder $translations) use ($search): void {
                            $translations->where('title', 'like', '%'.$search.'%');
                        });
                    }),
                TextColumn::make('publication_status')->badge()->label('Publication'),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with('translations')
                ->latest('id'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'publication';
    }

    public static function getPluralModelLabel(): string
    {
        return 'publications';
    }
}
