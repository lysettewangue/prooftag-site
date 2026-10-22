<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use App\Models\Career;
use App\Models\User;
use App\Support\StaffAccess;

final class CareerPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::canUpdateCommunications($user)
            || StaffAccess::can($user, StaffPermission::Translate)
            || $user->hasRole(StaffRole::ReviewerPublisher->value);
    }

    public function view(User $user, Career $career): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return StaffAccess::canUpdateCommunications($user);
    }

    public function update(User $user, Career $career): bool
    {
        return StaffAccess::canUpdateCommunications($user);
    }

    public function delete(User $user, Career $career): bool
    {
        return false;
    }

    public function forceDelete(User $user, Career $career): bool
    {
        return false;
    }

    public function translate(User $user, Career $career): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }

    public function publish(User $user, Career $career): bool
    {
        return StaffAccess::isAdmin($user)
            || $user->hasRole(StaffRole::ReviewerPublisher->value)
            || StaffAccess::can($user, StaffPermission::Publish);
    }
}
