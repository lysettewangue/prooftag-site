<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use App\Models\User;

final class StaffAccess
{
    public static function isStaff(User $user): bool
    {
        return $user->hasAnyRole(StaffRole::codes());
    }

    public static function isAdmin(User $user): bool
    {
        return $user->hasAnyRole([
            StaffRole::SuperAdmin->value,
            StaffRole::Admin->value,
        ]);
    }

    public static function canUpdateTechnical(User $user): bool
    {
        return $user->hasAnyRole([
            StaffRole::SuperAdmin->value,
            StaffRole::Admin->value,
            StaffRole::ContentManager->value,
            StaffRole::TechnicalEditor->value,
        ]);
    }

    public static function canUpdateCommunications(User $user): bool
    {
        return $user->hasAnyRole([
            StaffRole::SuperAdmin->value,
            StaffRole::Admin->value,
            StaffRole::ContentManager->value,
            StaffRole::CommunicationsEditor->value,
        ]);
    }

    public static function can(User $user, StaffPermission $permission): bool
    {
        return $user->can($permission->value);
    }
}
