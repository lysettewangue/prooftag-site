<?php

declare(strict_types=1);

namespace App\Filament\Resources\SitePages;

use App\Enums\Locale;
use App\Enums\PageCode;
use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use App\Filament\Resources\SitePages\Pages\EditSitePage;
use App\Filament\Resources\SitePages\Pages\ListSitePages;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\ContentBlockBuilder;
use App\Filament\Support\ForbidsCreateAndDelete;
use App\Filament\Support\LocaleTabs;
use App\Models\Page;
use App\Models\User;
use App\Support\StaffAccess;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class SitePageResource extends Resource
{
    use AuthorizesCmsResource;
    use ForbidsCreateAndDelete;

    protected static ?string $model = Page::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static string|\UnitEnum|null $navigationGroup = 'Corporate';

    protected static ?string $navigationLabel = 'Pages';

    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'pages';

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return StaffAccess::canUpdateCommunications($user)
            || StaffAccess::can($user, StaffPermission::Translate)
            || $user->hasRole(StaffRole::ReviewerPublisher->value);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identité')
                ->description('Onze codes figés. Le hub Contrôle technique a son propre module. Couleurs, polices, espacements et navigation primaire restent dans le code. VER-01 / VER-12 / VER-13 : ne pas publier un fait non tranché.')
                ->schema([
                    TextInput::make('code')->disabled(),
                    TextInput::make('publication_status')->disabled()->label('Publication'),
                ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.slug")->disabled()->label('Slug'),
                TextInput::make("translations.{$locale}.title")
                    ->required($locale === Locale::French->value)
                    ->label('Titre'),
                ContentBlockBuilder::make("translations.{$locale}.blocks"),
                TextInput::make("translations.{$locale}.meta_title"),
                Textarea::make("translations.{$locale}.meta_description"),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->badge()->label('Code'),
                TextColumn::make('title')
                    ->label('Titre')
                    ->getStateUsing(function (Page $record): string {
                        $translation = $record->translationFor('fr');

                        return is_object($translation) && isset($translation->title)
                            ? (string) $translation->title
                            : '—';
                    }),
                TextColumn::make('publication_status')->badge()->label('Publication'),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query
                ->with('translations')
                ->where('code', '!=', PageCode::TechnicalInspection)
                ->orderBy('id'));
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('code', '!=', PageCode::TechnicalInspection);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSitePages::route('/'),
            'edit' => EditSitePage::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'page';
    }

    public static function getPluralModelLabel(): string
    {
        return 'pages';
    }
}
