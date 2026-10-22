<?php

declare(strict_types=1);

namespace App\Filament\Resources\MediaLibrary\Pages;

use App\Actions\Media\SafeDeleteMedia;
use App\Actions\Media\TranslateMedia;
use App\Actions\Media\UpdateMedia;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Filament\Resources\MediaLibrary\MediaLibraryResource;
use App\Models\Media;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class EditMediaLibraryItem extends EditRecord
{
    protected static string $resource = MediaLibraryResource::class;

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('download')
                ->label('Télécharger')
                ->action(function (): StreamedResponse {
                    $record = $this->getRecord();

                    if (! $record instanceof Media) {
                        throw new Halt;
                    }

                    return $record->toResponse(request());
                }),
            DeleteAction::make()
                ->using(function (Model $record): void {
                    $user = auth()->user();

                    if (! $user instanceof User || ! $record instanceof Media) {
                        throw new Halt;
                    }

                    try {
                        (new SafeDeleteMedia)->handle($record, $user);
                    } catch (DomainRuleViolation $exception) {
                        Notification::make()->danger()->title($exception->getMessage())->send();

                        throw new Halt;
                    } catch (AuthorizationException $exception) {
                        Notification::make()->danger()->title('Action non autorisée.')->send();

                        throw new Halt;
                    }
                }),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! $record instanceof Media) {
            throw new Halt;
        }

        $user = auth()->user();

        if (! $user instanceof User) {
            throw new Halt;
        }

        try {
            if ($user->can('update', $record)) {
                (new UpdateMedia)->handle($record, $user, [
                    'alt.fr' => is_string($data['alt_fr'] ?? null) ? $data['alt_fr'] : '',
                    'rights' => is_string($data['rights'] ?? null) ? $data['rights'] : '',
                ]);
            }

            if ($user->can('translate', $record)) {
                (new TranslateMedia)->handle($record, $user, [
                    'alt.en' => is_string($data['alt_en'] ?? null) ? $data['alt_en'] : '',
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
        $record = $this->getRecord();

        if ($record instanceof Media) {
            $data['alt_fr'] = $record->altFr();
            $data['alt_en'] = $record->altEn();
            $data['rights'] = $record->rights();
        }

        return $data;
    }
}
