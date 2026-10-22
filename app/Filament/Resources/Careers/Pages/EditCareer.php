<?php

declare(strict_types=1);

namespace App\Filament\Resources\Careers\Pages;

use App\Actions\Corporate\TranslateCareer;
use App\Actions\Corporate\UpdateCareer;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Filament\Resources\Careers\CareerResource;
use App\Filament\Support\HasPublicationActions;
use App\Filament\Support\SavesViaDomainActions;
use App\Models\Career;
use App\Models\User;
use App\Support\BodyContent;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

final class EditCareer extends EditRecord
{
    use HasPublicationActions;
    use SavesViaDomainActions;

    protected static string $resource = CareerResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Career) {
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
        return Arr::only($data, ['kind', 'closes_at', 'application_url']);
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function translationPayload(string $locale, array $row): array
    {
        return array_merge(Arr::only($row, ['slug', 'title', 'meta_title', 'meta_description', 'canonical_path']), [
            'locale' => $locale,
            'body' => BodyContent::fromForm($row['body'] ?? null),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function translationFormState(string $locale, object $translation): array
    {
        $state = $translation->toArray();
        $state['locale'] = $locale;
        $state['body'] = BodyContent::toForm($state['body'] ?? null);

        return $state;
    }

    protected function performUpdate(Model $record, User $actor, array $payload): void
    {
        if (! $record instanceof Career) {
            throw new Halt;
        }

        (new UpdateCareer)->handle($record, $actor, $payload);
    }

    protected function performTranslate(Model $record, User $actor, array $payload): void
    {
        if (! $record instanceof Career) {
            throw new Halt;
        }

        (new TranslateCareer)->handle($record, $actor, $payload);
    }
}
