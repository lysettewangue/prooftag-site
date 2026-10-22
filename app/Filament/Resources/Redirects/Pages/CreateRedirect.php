<?php

declare(strict_types=1);

namespace App\Filament\Resources\Redirects\Pages;

use App\Actions\Seo\UpsertRedirect;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Filament\Resources\Redirects\RedirectResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

final class CreateRedirect extends CreateRecord
{
    protected static string $resource = RedirectResource::class;

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
            return (new UpsertRedirect)->handle($user, Arr::only($data, [
                'from_host',
                'from_path',
                'to_path',
                'status_code',
                'is_active',
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
