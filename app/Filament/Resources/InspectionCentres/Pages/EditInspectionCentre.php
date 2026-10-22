<?php

declare(strict_types=1);

namespace App\Filament\Resources\InspectionCentres\Pages;

use App\Actions\Centres\ArchiveCentre;
use App\Actions\Centres\MarkCentreTemporarilyUnavailable;
use App\Actions\Centres\TranslateCentre;
use App\Actions\Centres\UpdateCentre;
use App\Actions\Centres\VerifyCentre;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\CentreVerificationStatus;
use App\Enums\Locale;
use App\Filament\Resources\InspectionCentres\InspectionCentreResource;
use App\Filament\Support\SavesViaDomainActions;
use App\Models\InspectionCentre;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

final class EditInspectionCentre extends EditRecord
{
    use SavesViaDomainActions;

    protected static string $resource = InspectionCentreResource::class;

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('verify')
                ->label('Vérifier')
                ->visible(fn (): bool => $this->canRunVerification('verify', CentreVerificationStatus::Active))
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->runVerification(fn (InspectionCentre $centre, User $actor): InspectionCentre => (new VerifyCentre)->handle($centre, $actor));
                }),
            Action::make('unavailable')
                ->label('Marquer indisponible')
                ->visible(fn (): bool => $this->canRunVerification('markUnavailable', CentreVerificationStatus::TemporarilyUnavailable))
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->runVerification(fn (InspectionCentre $centre, User $actor): InspectionCentre => (new MarkCentreTemporarilyUnavailable)->handle($centre, $actor));
                }),
            Action::make('archive')
                ->label('Archiver')
                ->color('danger')
                ->visible(fn (): bool => $this->canRunVerification('archive', CentreVerificationStatus::Archived))
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->runVerification(fn (InspectionCentre $centre, User $actor): InspectionCentre => (new ArchiveCentre)->handle($centre, $actor));
                }),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = $this->hydrateTranslations($data);

        $verifiedAt = $data['last_verified_at'] ?? null;

        if ($verifiedAt instanceof \DateTimeInterface) {
            $data['last_verified_at'] = $verifiedAt->format('Y-m-d H:i:s');
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof InspectionCentre) {
            throw new Halt;
        }

        $actor = $this->actor();

        try {
            if ($actor->can('update', $record)) {
                $this->performUpdate($record, $actor, $this->parentPayload($data));

                $row = $data['translations'][Locale::French->value] ?? null;

                if (is_array($row)) {
                    $this->performTranslate($record, $actor, $this->translationPayload(Locale::French->value, $row));
                }
            }

            if ($actor->can('translate', $record)) {
                $row = $data['translations'][Locale::English->value] ?? null;

                if (is_array($row)) {
                    $this->performTranslate($record, $actor, $this->translationPayload(Locale::English->value, $row));
                }
            }
        } catch (DomainRuleViolation $exception) {
            Notification::make()->danger()->title($exception->getMessage())->send();

            throw new Halt;
        } catch (AuthorizationException $exception) {
            Notification::make()->danger()->title('Action non autorisée.')->send();

            throw new Halt;
        }

        return $record->refresh();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function parentPayload(array $data): array
    {
        return Arr::only($data, [
            'region_id',
            'operator_name',
            'city',
            'phone',
            'email',
            'latitude',
            'longitude',
            'sort_order',
        ]);
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function translationPayload(string $locale, array $row): array
    {
        return array_merge(Arr::only($row, [
            'slug',
            'name',
            'address_line',
            'body',
            'meta_title',
            'meta_description',
            'canonical_path',
        ]), [
            'locale' => $locale,
        ]);
    }

    protected function performUpdate(Model $record, User $actor, array $payload): void
    {
        if (! $record instanceof InspectionCentre) {
            throw new Halt;
        }

        (new UpdateCentre)->handle($record, $actor, $payload);
    }

    protected function performTranslate(Model $record, User $actor, array $payload): void
    {
        if (! $record instanceof InspectionCentre) {
            throw new Halt;
        }

        (new TranslateCentre)->handle($record, $actor, $payload);
    }

    private function canRunVerification(string $ability, CentreVerificationStatus $to): bool
    {
        $record = $this->getRecord();
        $user = auth()->user();

        if (! $record instanceof InspectionCentre || ! $user instanceof User) {
            return false;
        }

        return $record->canTransitionTo($to) && $user->can($ability, $record);
    }

    /**
     * @param  callable(InspectionCentre, User): InspectionCentre  $callback
     */
    private function runVerification(callable $callback): void
    {
        $record = $this->getRecord();
        $user = auth()->user();

        if (! $record instanceof InspectionCentre || ! $user instanceof User) {
            return;
        }

        try {
            $callback($record, $user);
            Notification::make()->success()->title('Statut mis à jour.')->send();
        } catch (DomainRuleViolation $exception) {
            Notification::make()->danger()->title($exception->getMessage())->send();

            throw new Halt;
        } catch (AuthorizationException $exception) {
            Notification::make()->danger()->title('Action non autorisée.')->send();

            throw new Halt;
        }

        $this->refreshFormData(['verification_status', 'last_verified_at', 'verified_by']);
    }
}
