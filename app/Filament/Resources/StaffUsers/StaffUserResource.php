<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffUsers;

use App\Enums\StaffRole;
use App\Filament\Resources\StaffUsers\Pages\CreateStaffUser;
use App\Filament\Resources\StaffUsers\Pages\EditStaffUser;
use App\Filament\Resources\StaffUsers\Pages\ListStaffUsers;
use App\Models\User;
use App\Support\StaffUsers;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class StaffUserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Utilisateurs';

    protected static ?int $navigationSort = 10;

    protected static ?string $slug = 'users';

    public static function canDelete(Model $record): bool
    {
        $actor = auth()->user();

        if (! $actor instanceof User || ! $record instanceof User) {
            return false;
        }

        if ($actor->is($record) || StaffUsers::isLastSuperAdmin($record)) {
            return false;
        }

        return $actor->can('delete', $record);
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Compte')
                ->description('Huit rôles figés. Un utilisateur a exactement un rôle. Le dernier Super administrateur ne peut pas être rétrogradé ni supprimé. L’administrateur ne gère pas les Super administrateurs.')
                ->schema([
                    TextInput::make('name')->required()->maxLength(255)->label('Nom'),
                    TextInput::make('email')->email()->required()->maxLength(255)->label('E-mail'),
                    TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->minLength(8)
                        ->label('Mot de passe'),
                    Select::make('role')
                        ->label('Rôle')
                        ->options(self::roleOptions())
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nom')->searchable(),
                TextColumn::make('email')->label('E-mail')->searchable(),
                TextColumn::make('role')
                    ->label('Rôle')
                    ->getStateUsing(function (User $record): string {
                        $code = $record->getRoleNames()->first();
                        $role = is_string($code) ? StaffRole::tryFrom($code) : null;

                        return $role instanceof StaffRole ? StaffUsers::label($role) : '—';
                    }),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->latest('id'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStaffUsers::route('/'),
            'create' => CreateStaffUser::route('/create'),
            'edit' => EditStaffUser::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'utilisateur';
    }

    public static function getPluralModelLabel(): string
    {
        return 'utilisateurs';
    }

    /**
     * @return array<string, string>
     */
    private static function roleOptions(): array
    {
        $actor = auth()->user();
        $includeSuper = $actor instanceof User && $actor->hasRole(StaffRole::SuperAdmin->value);
        $options = [];

        foreach (StaffRole::cases() as $role) {
            if ($role === StaffRole::SuperAdmin && ! $includeSuper) {
                continue;
            }

            $options[$role->value] = StaffUsers::label($role);
        }

        return $options;
    }
}
