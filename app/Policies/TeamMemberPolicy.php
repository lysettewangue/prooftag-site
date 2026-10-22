<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use App\Models\TeamMember;
use App\Models\User;
use App\Support\StaffAccess;

final class TeamMemberPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::canUpdateCommunications($user)
            || StaffAccess::can($user, StaffPermission::Translate)
            || $user->hasRole(StaffRole::ReviewerPublisher->value);
    }

    public function view(User $user, TeamMember $member): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return StaffAccess::canUpdateCommunications($user);
    }

    public function update(User $user, TeamMember $member): bool
    {
        return StaffAccess::canUpdateCommunications($user);
    }

    public function delete(User $user, TeamMember $member): bool
    {
        return false;
    }

    public function forceDelete(User $user, TeamMember $member): bool
    {
        return false;
    }

    public function translate(User $user, TeamMember $member): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }

    public function publish(User $user, TeamMember $member): bool
    {
        return StaffAccess::isAdmin($user)
            || $user->hasRole(StaffRole::ReviewerPublisher->value)
            || StaffAccess::can($user, StaffPermission::Publish);
    }
}
