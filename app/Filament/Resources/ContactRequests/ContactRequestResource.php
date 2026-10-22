<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactRequests;

use App\Enums\ContactRequestStatus;
use App\Filament\Resources\ContactRequests\Pages\EditContactRequest;
use App\Filament\Resources\ContactRequests\Pages\ListContactRequests;
use App\Filament\Support\AuthorizesCmsResource;
use App\Models\ContactRequest;
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

final class ContactRequestResource extends Resource
{
    use AuthorizesCmsResource;

    protected static ?string $model = ContactRequest::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static string|\UnitEnum|null $navigationGroup = 'Support';

    protected static ?string $navigationLabel = 'Demandes';

    protected static ?int $navigationSort = 80;

    protected static ?string $slug = 'contact-requests';

    public static function canCreate(): bool
    {
        return false;
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Demande')
                ->description('A-13 : Nouveau → En cours → Répondu → Clos, ou Spam. Pas de publication. Création uniquement via le formulaire public. Pas de suppression : clore ou marquer spam.')
                ->schema([
                    TextInput::make('status')->disabled()->label('Statut'),
                    TextInput::make('name')->disabled()->label('Nom'),
                    TextInput::make('email')->disabled()->label('E-mail'),
                    TextInput::make('phone')->disabled()->label('Téléphone'),
                    TextInput::make('subject')->disabled()->label('Objet'),
                    Textarea::make('message')->disabled()->rows(8)->label('Message'),
                    TextInput::make('locale')->disabled()->label('Locale'),
                    TextInput::make('ip')->disabled()->label('IP'),
                    TextInput::make('user_agent')->disabled()->label('User-agent'),
                    TextInput::make('notified_at')->disabled()->label('Notifié le'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->dateTime()->label('Reçue')->sortable(),
                TextColumn::make('name')->label('Nom')->searchable(),
                TextColumn::make('email')->label('E-mail')->searchable(),
                TextColumn::make('subject')->label('Objet')->limit(40),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(function (ContactRequestStatus|string|null $state): string {
                        $status = $state instanceof ContactRequestStatus
                            ? $state
                            : ContactRequestStatus::tryFrom((string) $state);

                        return match ($status) {
                            ContactRequestStatus::New => 'Nouveau',
                            ContactRequestStatus::InProgress => 'En cours',
                            ContactRequestStatus::Responded => 'Répondu',
                            ContactRequestStatus::Closed => 'Clos',
                            ContactRequestStatus::Spam => 'Spam',
                            default => '—',
                        };
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        ContactRequestStatus::New->value => 'Nouveau',
                        ContactRequestStatus::InProgress->value => 'En cours',
                        ContactRequestStatus::Responded->value => 'Répondu',
                        ContactRequestStatus::Closed->value => 'Clos',
                        ContactRequestStatus::Spam->value => 'Spam',
                    ]),
            ])
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->latest('id'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactRequests::route('/'),
            'edit' => EditContactRequest::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'demande';
    }

    public static function getPluralModelLabel(): string
    {
        return 'demandes';
    }
}
