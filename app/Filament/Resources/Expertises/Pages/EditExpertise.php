<?php

declare(strict_types=1);

namespace App\Filament\Resources\Expertises\Pages;

use App\Actions\Expertise\TranslateExpertise;
use App\Actions\Expertise\UpdateExpertise;
use App\Filament\Resources\Expertises\ExpertiseResource;
use App\Filament\Support\HasPublicationActions;
use App\Filament\Support\SavesViaDomainActions;
use App\Models\Expertise;
use App\Models\User;
use App\Support\BodyContent;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

final class EditExpertise extends EditRecord
{
    use HasPublicationActions;
    use SavesViaDomainActions;

    protected static string $resource = ExpertiseResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function parentPayload(array $data): array
    {
        return Arr::only($data, ['icon']);
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function translationPayload(string $locale, array $row): array
    {
        return array_merge(Arr::only($row, ['slug', 'title', 'summary', 'meta_title', 'meta_description', 'canonical_path']), [
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
        if (! $record instanceof Expertise) {
            throw new Halt;
        }

        (new UpdateExpertise)->handle($record, $actor, $payload);
    }

    protected function performTranslate(Model $record, User $actor, array $payload): void
    {
        if (! $record instanceof Expertise) {
            throw new Halt;
        }

        (new TranslateExpertise)->handle($record, $actor, $payload);
    }
}
