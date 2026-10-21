<?php

declare(strict_types=1);

namespace App\Filament\Resources\SecurityPoints;

use App\Filament\Clusters\TechnicalInspection\TechnicalInspectionCluster;
use App\Filament\Resources\SecurityPoints\Pages\EditSecurityPoint;
use App\Filament\Resources\SecurityPoints\Pages\ListSecurityPoints;
use App\Filament\Support\AuthorizesCmsResource;
use App\Filament\Support\ForbidsCreateAndDelete;
use App\Filament\Support\LocaleTabs;
use App\Models\SecurityPoint;
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

final class SecurityPointResource extends Resource
{
    use AuthorizesCmsResource;
    use ForbidsCreateAndDelete;

    protected static ?string $model = SecurityPoint::class;

    protected static ?string $cluster = TechnicalInspectionCluster::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static ?string $navigationLabel = '12 points';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'security-points';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identité')
                ->description('Douze points figés. L’ordre 1–12 est l’identité. Pas de 13ᵉ point. Titres courts, pas en capitales.')
                ->schema([
                    TextInput::make('sort_order')->disabled()->label('Ordre'),
                    TextInput::make('publication_status')->disabled(),
                ]),
            LocaleTabs::make(fn (string $locale): array => [
                TextInput::make("translations.{$locale}.title")->required()->label('Titre'),
                Textarea::make("translations.{$locale}.body")->label('Explication')->rows(8),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();
        $canReorder = $user instanceof User && StaffAccess::canUpdateTechnical($user);

        return $table
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('title')
                    ->label('Titre')
                    ->getStateUsing(function (SecurityPoint $record): string {
                        $translation = $record->translationFor('fr');

                        return is_object($translation) && isset($translation->title)
                            ? (string) $translation->title
                            : (string) $record->sort_order;
                    }),
                TextColumn::make('publication_status')->badge(),
            ])
            ->defaultSort('sort_order')
            ->paginated(false)
            ->reorderable('sort_order', $canReorder);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSecurityPoints::route('/'),
            'edit' => EditSecurityPoint::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'point de sécurisation';
    }

    public static function getPluralModelLabel(): string
    {
        return 'points de sécurisation';
    }
}
