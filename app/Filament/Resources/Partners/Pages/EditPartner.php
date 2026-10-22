<?php

declare(strict_types=1);

namespace App\Filament\Resources\Partners\Pages;

use App\Actions\Corporate\TranslatePartner;
use App\Actions\Corporate\UpdatePartner;
use App\Actions\Media\AttachMedia;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Filament\Resources\Partners\PartnerResource;
use App\Filament\Support\HasPublicationActions;
use App\Filament\Support\SavesViaDomainActions;
use App\Models\Partner;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

final class EditPartner extends EditRecord
{
    use HasPublicationActions;
    use SavesViaDomainActions;

    protected static string $resource = PartnerResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Partner) {
            throw new Halt;
        }

        $logo = $data['logo'] ?? null;
        $altFr = is_string($data['logo_alt_fr'] ?? null) ? $data['logo_alt_fr'] : '';
        $altEn = is_string($data['logo_alt_en'] ?? null) ? $data['logo_alt_en'] : '';
        unset($data['logo'], $data['logo_alt_fr'], $data['logo_alt_en']);

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

            $file = is_array($logo) ? (array_values($logo)[0] ?? null) : $logo;

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
    protected function parentPayload(array $data): array
    {
        return Arr::only($data, ['name', 'sort_order', 'url']);
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function translationPayload(string $locale, array $row): array
    {
        return array_merge(Arr::only($row, ['slug', 'blurb', 'meta_title', 'meta_description', 'canonical_path']), [
            'locale' => $locale,
        ]);
    }

    protected function performUpdate(Model $record, User $actor, array $payload): void
    {
        if (! $record instanceof Partner) {
            throw new Halt;
        }

        (new UpdatePartner)->handle($record, $actor, $payload);
    }

    protected function performTranslate(Model $record, User $actor, array $payload): void
    {
        if (! $record instanceof Partner) {
            throw new Halt;
        }

        (new TranslatePartner)->handle($record, $actor, $payload);
    }
}
