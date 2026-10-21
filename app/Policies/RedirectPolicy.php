<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Models\Redirect;
use App\Models\User;
use App\Support\StaffAccess;

final class RedirectPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::can($user, StaffPermission::ManageRedirects);
    }

    public function view(User $user, Redirect $redirect): bool
    {
        return StaffAccess::can($user, StaffPermission::ManageRedirects);
    }

    public function create(User $user): bool
    {
        return StaffAccess::can($user, StaffPermission::ManageRedirects);
    }

    public function update(User $user, Redirect $redirect): bool
    {
        return StaffAccess::can($user, StaffPermission::ManageRedirects);
    }
}
