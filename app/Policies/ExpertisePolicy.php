<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use App\Models\Expertise;
use App\Models\User;
use App\Support\StaffAccess;

final class ExpertisePolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::canUpdateTechnical($user)
            || StaffAccess::can($user, StaffPermission::Translate)
            || $user->hasRole(StaffRole::ReviewerPublisher->value);
    }

    public function view(User $user, Expertise $expertise): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Expertise $expertise): bool
    {
        return StaffAccess::canUpdateTechnical($user);
    }

    public function delete(User $user, Expertise $expertise): bool
    {
        return false;
    }

    public function translate(User $user, Expertise $expertise): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }

    public function publish(User $user, Expertise $expertise): bool
    {
        return StaffAccess::isAdmin($user)
            || $user->hasRole(StaffRole::ReviewerPublisher->value);
    }
}
