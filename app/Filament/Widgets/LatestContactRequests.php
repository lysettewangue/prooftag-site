<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\ContactRequestStatus;
use App\Filament\Resources\ContactRequests\ContactRequestResource;
use App\Models\ContactRequest;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Model;

final class LatestContactRequests extends TableWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Dernières demandes de contact')
            ->description('Les 8 plus récentes, tous statuts confondus.')
            ->query(
                ContactRequest::query()->latest('id')->limit(8),
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Reçue')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(false),
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(false),
                TextColumn::make('subject')
                    ->label('Objet')
                    ->limit(42)
                    ->wrap(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (ContactRequestStatus|string|null $state): string => match (
                        $state instanceof ContactRequestStatus
                            ? $state
                            : ContactRequestStatus::tryFrom((string) $state)
                    ) {
                        ContactRequestStatus::New => 'danger',
                        ContactRequestStatus::InProgress => 'primary',
                        ContactRequestStatus::Responded => 'success',
                        ContactRequestStatus::Closed => 'gray',
                        ContactRequestStatus::Spam => 'gray',
                        default => 'gray',
                    })
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
            ->recordUrl(fn (Model $record): string => ContactRequestResource::getUrl('edit', ['record' => $record]))
            ->paginated(false)
            ->headerActions([
                Action::make('all')
                    ->label('Voir toutes')
                    ->url(ContactRequestResource::getUrl('index')),
            ]);
    }
}
