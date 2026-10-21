<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Models\Resource;
use App\Models\User;
use App\Support\StaffAccess;

final class ResourcePolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::isStaff($user);
    }

    public function view(User $user, Resource $resource): bool
    {
        return StaffAccess::isStaff($user);
    }

    public function create(User $user): bool
    {
        return StaffAccess::canUpdateTechnical($user);
    }

    public function update(User $user, Resource $resource): bool
    {
        return StaffAccess::canUpdateTechnical($user);
    }

    public function translate(User $user, Resource $resource): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }

    public function publish(User $user, Resource $resource): bool
    {
        return StaffAccess::can($user, StaffPermission::Publish);
    }
}
