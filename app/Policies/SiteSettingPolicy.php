<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\StaffAccess;

final class SiteSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::isAdmin($user) || StaffAccess::can($user, StaffPermission::Translate);
    }

    public function view(User $user, SiteSetting $setting): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, SiteSetting $setting): bool
    {
        return StaffAccess::isAdmin($user);
    }

    public function delete(User $user, SiteSetting $setting): bool
    {
        return false;
    }

    public function translate(User $user, SiteSetting $setting): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }
}
