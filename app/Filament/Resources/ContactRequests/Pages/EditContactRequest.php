<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactRequests\Pages;

use App\Actions\Contact\UpdateContactRequestStatus;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\ContactRequestStatus;
use App\Filament\Resources\ContactRequests\ContactRequestResource;
use App\Models\ContactRequest;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;

final class EditContactRequest extends EditRecord
{
    protected static string $resource = ContactRequestResource::class;

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();

        if (! $record instanceof ContactRequest) {
            return [];
        }

        $actions = [];

        foreach ($record->status->allowedTransitions() as $to) {
            $actions[] = Action::make($to->value)
                ->label($this->statusLabel($to))
                ->color($to === ContactRequestStatus::Spam ? 'danger' : 'primary')
                ->requiresConfirmation()
                ->action(function () use ($to): void {
                    $this->runTransition($to);
                });
        }

        return $actions;
    }

    /**
     * @return array<int, Action>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction(),
        ];
    }

    private function runTransition(ContactRequestStatus $to): void
    {
        $record = $this->getRecord();
        $user = auth()->user();

        if (! $record instanceof ContactRequest || ! $user instanceof User) {
            return;
        }

        try {
            (new UpdateContactRequestStatus)->handle($record, $user, $to);
            Notification::make()->success()->title('Statut mis à jour.')->send();
        } catch (DomainRuleViolation $exception) {
            Notification::make()->danger()->title($exception->getMessage())->send();

            throw new Halt;
        } catch (AuthorizationException $exception) {
            Notification::make()->danger()->title('Action non autorisée.')->send();

            throw new Halt;
        }

        $this->refreshFormData(['status']);
    }

    private function statusLabel(ContactRequestStatus $status): string
    {
        return match ($status) {
            ContactRequestStatus::New => 'Nouveau',
            ContactRequestStatus::InProgress => 'En cours',
            ContactRequestStatus::Responded => 'Répondu',
            ContactRequestStatus::Closed => 'Clos',
            ContactRequestStatus::Spam => 'Spam',
        };
    }
}
