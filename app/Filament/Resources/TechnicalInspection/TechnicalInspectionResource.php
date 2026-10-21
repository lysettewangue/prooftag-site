<?php

declare(strict_types=1);

namespace App\Filament\Resources\TechnicalInspection;

use App\Enums\PageCode;
use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use App\Filament\Clusters\TechnicalInspection\TechnicalInspectionCluster;
use App\Filament\Resources\TechnicalInspection\Pages\EditTechnicalInspection;
use App\Filament\Resources\TechnicalInspection\Pages\ListTechnicalInspections;
use App\Filament\Support\AuthorizesCmsResource;
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

final class TechnicalInspectionResource extends Resource
{
    use AuthorizesCmsResource;
    use ForbidsCreateAndDelete;

    protected static ?string $model = Page::class;

    protected static ?string $cluster = TechnicalInspectionCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Aperçu';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'overview';

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        return StaffAccess::canUpdateTechnical($user)
            || StaffAccess::can($user, StaffPermission::Translate)
            || $user->hasRole(StaffRole::ReviewerPublisher->value);
    }

    public static function getNavigationUrl(): string
    {
        return self::getUrl('edit', ['record' => self::hub()]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identité')
                ->description('Hub Contrôle technique. Pas un produit. Les 12 points et les équipements sont d’autres modules.')
                ->schema([
                    TextInput::make('code')->disabled(),
                    TextInput::make('publication_status')->disabled(),
                ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.slug")->disabled()->label('Slug'),
                TextInput::make("translations.{$locale}.title")->required()->label('Titre'),
                Textarea::make("translations.{$locale}.overview")
                    ->label('Aperçu')
                    ->rows(8)
                    ->helperText('Texte d’introduction du hub. Un seul bloc rich_text pour V1.'),
                TextInput::make("translations.{$locale}.meta_title"),
                Textarea::make("translations.{$locale}.meta_description"),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->badge(),
                TextColumn::make('publication_status')->badge(),
            ])
            ->paginated(false);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('code', PageCode::TechnicalInspection);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTechnicalInspections::route('/'),
            'edit' => EditTechnicalInspection::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'contrôle technique';
    }

    public static function getPluralModelLabel(): string
    {
        return 'contrôle technique';
    }

    public static function hub(): Page
    {
        return Page::query()->where('code', PageCode::TechnicalInspection)->firstOrFail();
    }
}
