<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffUsers\Pages;

use App\Actions\Staff\DeleteStaffUser;
use App\Actions\Staff\UpdateStaffUser;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Filament\Resources\StaffUsers\StaffUserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

final class EditStaffUser extends EditRecord
{
    protected static string $resource = StaffUserResource::class;

    /**
     * @return array<int, DeleteAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->using(function (Model $record): void {
                    $actor = auth()->user();

                    if (! $actor instanceof User || ! $record instanceof User) {
                        throw new Halt;
                    }

                    try {
                        (new DeleteStaffUser)->handle($record, $actor);
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
        $actor = auth()->user();

        if (! $actor instanceof User || ! $record instanceof User) {
            throw new Halt;
        }

        try {
            return (new UpdateStaffUser)->handle($record, $actor, Arr::only($data, [
                'name',
                'email',
                'password',
                'role',
            ]));
        } catch (DomainRuleViolation $exception) {
            Notification::make()->danger()->title($exception->getMessage())->send();

            throw new Halt;
        } catch (AuthorizationException $exception) {
            Notification::make()->danger()->title('Action non autorisée.')->send();

            throw new Halt;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        if ($record instanceof User) {
            $data['role'] = $record->getRoleNames()->first();
            $data['password'] = '';
        }

        return $data;
    }
}
