<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts\Pages;

use App\Actions\Editorial\TranslatePost;
use App\Actions\Editorial\UpdatePost;
use App\Actions\Media\AttachMedia;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Support\HasPublicationActions;
use App\Filament\Support\SavesViaDomainActions;
use App\Models\Post;
use App\Models\User;
use App\Support\BodyContent;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

final class EditPost extends EditRecord
{
    use HasPublicationActions;
    use SavesViaDomainActions;

    protected static string $resource = PostResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Post) {
            throw new Halt;
        }

        $hero = $data['hero'] ?? null;
        $altFr = is_string($data['hero_alt_fr'] ?? null) ? $data['hero_alt_fr'] : '';
        $altEn = is_string($data['hero_alt_en'] ?? null) ? $data['hero_alt_en'] : '';
        unset($data['hero'], $data['hero_alt_fr'], $data['hero_alt_en']);

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

            $file = is_array($hero) ? (array_values($hero)[0] ?? null) : $hero;

            if ($file instanceof UploadedFile) {
                (new AttachMedia)->handle($record, $actor, $file, 'images', [
                    'alt.fr' => $altFr,
                    'alt.en' => $altEn,
                    'rights' => 'PROOFTAG-CATIS',
                ]);
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
        $data = $this->hydrateTranslations($data);
        $record = $this->getRecord();

        if ($record instanceof Post) {
            $data['category_ids'] = $record->categories()->pluck('categories.id')->all();
            $data['product_ids'] = $record->products()->pluck('products.id')->all();
            $data['expertise_ids'] = $record->expertises()->pluck('expertises.id')->all();
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function parentPayload(array $data): array
    {
        return Arr::only($data, ['kind', 'category_ids', 'product_ids', 'expertise_ids']);
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
        if (! $record instanceof Post) {
            throw new Halt;
        }

        (new UpdatePost)->handle($record, $actor, $payload);
    }

    protected function performTranslate(Model $record, User $actor, array $payload): void
    {
        if (! $record instanceof Post) {
            throw new Halt;
        }

        (new TranslatePost)->handle($record, $actor, $payload);
    }
}
