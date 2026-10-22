<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffUsers\Pages;

use App\Actions\Staff\CreateStaffUser as CreateStaffUserAction;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Filament\Resources\StaffUsers\StaffUserResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

final class CreateStaffUser extends CreateRecord
{
    protected static string $resource = StaffUserResource::class;

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
            return (new CreateStaffUserAction)->handle($user, Arr::only($data, [
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
}
