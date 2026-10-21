<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Models\InspectionCentre;
use App\Models\User;
use App\Support\StaffAccess;

final class InspectionCentrePolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::isStaff($user);
    }

    public function view(User $user, InspectionCentre $centre): bool
    {
        return StaffAccess::isStaff($user);
    }

    public function create(User $user): bool
    {
        return StaffAccess::canUpdateTechnical($user);
    }

    public function update(User $user, InspectionCentre $centre): bool
    {
        return StaffAccess::canUpdateTechnical($user);
    }

    public function translate(User $user, InspectionCentre $centre): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }

    public function verify(User $user, InspectionCentre $centre): bool
    {
        return StaffAccess::can($user, StaffPermission::VerifyCentre);
    }

    public function markUnavailable(User $user, InspectionCentre $centre): bool
    {
        return StaffAccess::isAdmin($user);
    }

    public function archive(User $user, InspectionCentre $centre): bool
    {
        return StaffAccess::isAdmin($user);
    }
}
