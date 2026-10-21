<?php

declare(strict_types=1);

namespace App\Filament\Support;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;

trait SavesViaDomainActions
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    abstract protected function parentPayload(array $data): array;

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    abstract protected function translationPayload(string $locale, array $row): array;

    abstract protected function performUpdate(Model $record, User $actor, array $payload): void;

    abstract protected function performTranslate(Model $record, User $actor, array $payload): void;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $actor = $this->actor();

        try {
            if ($actor->can('update', $record)) {
                $this->performUpdate($record, $actor, $this->parentPayload($data));
            }

            if ($actor->can('translate', $record)) {
                foreach (Locale::codes() as $locale) {
                    $row = $data['translations'][$locale] ?? null;

                    if (! is_array($row)) {
                        continue;
                    }

                    $this->performTranslate($record, $actor, $this->translationPayload($locale, $row));
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
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->hydrateTranslations($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function hydrateTranslations(array $data): array
    {
        $record = $this->getRecord();

        foreach (Locale::codes() as $locale) {
            $translation = method_exists($record, 'translationFor')
                ? $record->translationFor($locale)
                : null;

            $data['translations'][$locale] = $translation !== null
                ? $this->translationFormState($locale, $translation)
                : ['locale' => $locale];
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    protected function translationFormState(string $locale, object $translation): array
    {
        $state = $translation->toArray();
        $state['locale'] = $locale;

        return $state;
    }

    protected function actor(): User
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            throw new Halt;
        }

        return $user;
    }
}
