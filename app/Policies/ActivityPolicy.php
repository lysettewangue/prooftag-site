<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use App\Support\StaffAccess;

final class ActivityPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::isAdmin($user);
    }

    public function view(User $user, Activity $activity): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Activity $activity): bool
    {
        return false;
    }

    public function delete(User $user, Activity $activity): bool
    {
        return false;
    }

    public function forceDelete(User $user, Activity $activity): bool
    {
        return false;
    }
}
