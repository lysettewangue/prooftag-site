<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Permission\Events\PermissionAttachedEvent;
use Spatie\Permission\Events\PermissionDetachedEvent;
use Spatie\Permission\Events\RoleAttachedEvent;
use Spatie\Permission\Events\RoleDetachedEvent;

final class LogStaffAuthorization
{
    public function roleAttached(RoleAttachedEvent $event): void
    {
        $this->write('role_attached', $event->model, $event->rolesOrIds);
    }

    public function roleDetached(RoleDetachedEvent $event): void
    {
        $this->write('role_detached', $event->model, $event->rolesOrIds);
    }

    public function permissionAttached(PermissionAttachedEvent $event): void
    {
        $this->write('permission_attached', $event->model, $event->permissionsOrIds);
    }

    public function permissionDetached(PermissionDetachedEvent $event): void
    {
        $this->write('permission_detached', $event->model, $event->permissionsOrIds);
    }

    private function write(string $event, Model $subject, mixed $payload): void
    {
        activity('staff')
            ->causedBy(auth()->user())
            ->performedOn($subject)
            ->event($event)
            ->withProperties(['payload' => $this->normalize($payload)])
            ->log($event);
    }

    private function normalize(mixed $value): mixed
    {
        if ($value instanceof Collection) {
            $value = $value->all();
        }

        if ($value instanceof Model) {
            return $value->getKey();
        }

        if (is_array($value)) {
            return array_map(
                static fn (mixed $item): mixed => $item instanceof Model ? $item->getKey() : $item,
                $value,
            );
        }

        return $value;
    }
}
