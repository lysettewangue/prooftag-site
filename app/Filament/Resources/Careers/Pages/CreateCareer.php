<?php

declare(strict_types=1);

namespace App\Filament\Resources\Careers\Pages;

use App\Actions\Corporate\CreateCareer as CreateCareerAction;
use App\Actions\Corporate\TranslateCareer;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Filament\Resources\Careers\CareerResource;
use App\Models\Career;
use App\Models\User;
use App\Support\BodyContent;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final class CreateCareer extends CreateRecord
{
    protected static string $resource = CareerResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            throw new Halt;
        }

        try {
            $career = DB::transaction(function () use ($user, $data): Career {
                $created = (new CreateCareerAction)->handle($user, Arr::only($data, ['kind', 'closes_at', 'application_url']));

                $french = $data['translations'][Locale::French->value] ?? null;

                if (! is_array($french)) {
                    throw new DomainRuleViolation('French title is required.');
                }

                (new TranslateCareer)->handle($created, $user, $this->translationPayload(Locale::French->value, $french));

                if ($user->can('translate', $created)) {
                    $english = $data['translations'][Locale::English->value] ?? null;

                    if (is_array($english) && filled($english['title'] ?? null)) {
                        (new TranslateCareer)->handle($created, $user, $this->translationPayload(Locale::English->value, $english));
                    }
                }

                return $created;
            });
        } catch (DomainRuleViolation $exception) {
            Notification::make()->danger()->title($exception->getMessage())->send();

            throw new Halt;
        } catch (AuthorizationException $exception) {
            Notification::make()->danger()->title('Action non autorisée.')->send();

            throw new Halt;
        }

        return $career;
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

    protected function getRedirectUrl(): string
    {
        $record = $this->getRecord();

        if (! $record instanceof Career) {
            return CareerResource::getUrl('index');
        }

        return CareerResource::getUrl('edit', ['record' => $record]);
    }
}
