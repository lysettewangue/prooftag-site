<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use App\Models\InspectionCentre;
use App\Models\User;
use App\Support\StaffAccess;

final class InspectionCentrePolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::canUpdateTechnical($user)
            || StaffAccess::can($user, StaffPermission::Translate)
            || StaffAccess::can($user, StaffPermission::VerifyCentre)
            || $user->hasRole(StaffRole::SupportAgent->value);
    }

    public function view(User $user, InspectionCentre $centre): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return StaffAccess::canUpdateTechnical($user);
    }

    public function update(User $user, InspectionCentre $centre): bool
    {
        return StaffAccess::canUpdateTechnical($user);
    }

    public function delete(User $user, InspectionCentre $centre): bool
    {
        return false;
    }

    public function forceDelete(User $user, InspectionCentre $centre): bool
    {
        return false;
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
