<?php

declare(strict_types=1);

namespace App\Filament\Resources\SecurityPoints\Pages;

use App\Actions\Inspection\ReorderSecurityPoints;
use App\Domain\Exceptions\DomainRuleViolation;
use App\Filament\Resources\SecurityPoints\SecurityPointResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Exceptions\Halt;
use Illuminate\Auth\Access\AuthorizationException;

final class ListSecurityPoints extends ListRecords
{
    protected static string $resource = SecurityPointResource::class;

    /**
     * @param  array<int|string>  $order
     */
    public function reorderTable(array $order, int|string|null $draggedRecordKey = null): void
    {
        if (! $this->getTable()->isReorderable()) {
            return;
        }

        $user = auth()->user();

        if (! $user instanceof User) {
            return;
        }

        try {
            (new ReorderSecurityPoints)->handle($user, array_values($order));
        } catch (DomainRuleViolation $exception) {
            Notification::make()->danger()->title($exception->getMessage())->send();

            throw new Halt;
        } catch (AuthorizationException $exception) {
            Notification::make()->danger()->title('Action non autorisée.')->send();

            throw new Halt;
        }

        $this->flushCachedTableRecords();
    }
}
