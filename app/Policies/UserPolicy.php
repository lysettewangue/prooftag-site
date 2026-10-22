<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use App\Models\User;
use App\Support\StaffAccess;
use App\Support\StaffUsers;

final class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::can($user, StaffPermission::ManageUsers);
    }

    public function view(User $user, User $staff): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return StaffAccess::can($user, StaffPermission::ManageUsers);
    }

    public function update(User $user, User $staff): bool
    {
        if (! StaffAccess::can($user, StaffPermission::ManageUsers)) {
            return false;
        }

        if ($staff->hasRole(StaffRole::SuperAdmin->value) && ! $user->hasRole(StaffRole::SuperAdmin->value)) {
            return false;
        }

        return true;
    }

    public function delete(User $user, User $staff): bool
    {
        if ($user->is($staff) || StaffUsers::isLastSuperAdmin($staff)) {
            return false;
        }

        return $this->update($user, $staff);
    }

    public function forceDelete(User $user, User $staff): bool
    {
        return false;
    }
}
