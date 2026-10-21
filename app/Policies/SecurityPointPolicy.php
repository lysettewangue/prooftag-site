<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use App\Models\SecurityPoint;
use App\Models\User;
use App\Support\StaffAccess;

final class SecurityPointPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::canUpdateTechnical($user)
            || StaffAccess::can($user, StaffPermission::Translate)
            || $user->hasRole(StaffRole::ReviewerPublisher->value);
    }

    public function view(User $user, SecurityPoint $point): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, SecurityPoint $point): bool
    {
        return StaffAccess::canUpdateTechnical($user);
    }

    public function delete(User $user, SecurityPoint $point): bool
    {
        return false;
    }

    public function translate(User $user, SecurityPoint $point): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }

    public function publish(User $user, SecurityPoint $point): bool
    {
        return StaffAccess::isAdmin($user)
            || $user->hasRole(StaffRole::ReviewerPublisher->value);
    }
}
