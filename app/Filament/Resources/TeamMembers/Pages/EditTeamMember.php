<?php

declare(strict_types=1);

namespace App\Filament\Resources\TeamMembers\Pages;

use App\Actions\Corporate\TranslateTeamMember;
use App\Actions\Corporate\UpdateTeamMember;
use App\Actions\Media\AttachMedia;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\Locale;
use App\Filament\Resources\TeamMembers\TeamMemberResource;
use App\Filament\Support\HasPublicationActions;
use App\Filament\Support\SavesViaDomainActions;
use App\Models\TeamMember;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

final class EditTeamMember extends EditRecord
{
    use HasPublicationActions;
    use SavesViaDomainActions;

    protected static string $resource = TeamMemberResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof TeamMember) {
            throw new Halt;
        }

        $photo = $data['photo'] ?? null;
        $altFr = is_string($data['photo_alt_fr'] ?? null) ? $data['photo_alt_fr'] : '';
        $altEn = is_string($data['photo_alt_en'] ?? null) ? $data['photo_alt_en'] : '';
        unset($data['photo'], $data['photo_alt_fr'], $data['photo_alt_en']);

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

            $file = is_array($photo) ? (array_values($photo)[0] ?? null) : $photo;

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
        return Arr::only($data, ['full_name', 'sort_order']);
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    protected function translationPayload(string $locale, array $row): array
    {
        return array_merge(Arr::only($row, ['slug', 'title', 'bio', 'meta_title', 'meta_description', 'canonical_path']), [
            'locale' => $locale,
        ]);
    }

    protected function performUpdate(Model $record, User $actor, array $payload): void
    {
        if (! $record instanceof TeamMember) {
            throw new Halt;
        }

        (new UpdateTeamMember)->handle($record, $actor, $payload);
    }

    protected function performTranslate(Model $record, User $actor, array $payload): void
    {
        if (! $record instanceof TeamMember) {
            throw new Halt;
        }

        (new TranslateTeamMember)->handle($record, $actor, $payload);
    }
}
