<?php

declare(strict_types=1);

namespace App\Filament\Support;

use App\Actions\Publication\TransitionPublication;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\PublicationStatus;
use App\Models\User;
use DateTimeImmutable;
use DateTimeInterface;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;

trait HasPublicationActions
{
    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return $this->publicationActions();
    }

    /**
     * @return array<int, Action>
     */
    protected function publicationActions(): array
    {
        return [
            $this->transitionAction('submit', 'Soumettre à relecture', PublicationStatus::UnderReview, 'update'),
            $this->transitionAction('approve', 'Approuver', PublicationStatus::Approved, 'update'),
            $this->transitionAction('publish', 'Publier', PublicationStatus::Published, 'publish'),
            Action::make('schedule')
                ->label('Programmer')
                ->visible(fn (): bool => $this->canTransition(PublicationStatus::Scheduled, 'publish'))
                ->form([
                    DateTimePicker::make('scheduled_for')
                        ->label('Date de publication')
                        ->required()
                        ->after('now'),
                ])
                ->action(function (array $data): void {
                    $when = $data['scheduled_for'] ?? null;
                    $this->runTransition(PublicationStatus::Scheduled, is_string($when) ? new DateTimeImmutable($when) : null);
                }),
            $this->transitionAction('archive', 'Archiver', PublicationStatus::Archived, 'publish')
                ->color('danger')
                ->requiresConfirmation(),
        ];
    }

    protected function transitionAction(string $name, string $label, PublicationStatus $to, string $ability): Action
    {
        return Action::make($name)
            ->label($label)
            ->visible(fn (): bool => $this->canTransition($to, $ability))
            ->action(function () use ($to): void {
                $this->runTransition($to);
            });
    }

    protected function canTransition(PublicationStatus $to, string $ability): bool
    {
        $record = $this->getRecord();
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        if (! method_exists($record, 'canTransitionTo') || ! $record->canTransitionTo($to)) {
            return false;
        }

        if ($to === PublicationStatus::Approved) {
            return $user->can('update', $record) || $user->can('publish', $record);
        }

        return $user->can($ability, $record);
    }

    protected function runTransition(PublicationStatus $to, ?DateTimeInterface $when = null): void
    {
        $record = $this->getRecord();
        $user = auth()->user();

        if (! $user instanceof User) {
            return;
        }

        try {
            (new TransitionPublication)->handle($record, $to, $user, $when);
            Notification::make()->success()->title('Statut mis à jour.')->send();
        } catch (DomainRuleViolation $exception) {
            Notification::make()->danger()->title($exception->getMessage())->send();

            throw new Halt;
        } catch (AuthorizationException $exception) {
            Notification::make()->danger()->title('Action non autorisée.')->send();

            throw new Halt;
        }

        $this->refreshFormData(['publication_status', 'published_at', 'scheduled_for']);
    }
}
