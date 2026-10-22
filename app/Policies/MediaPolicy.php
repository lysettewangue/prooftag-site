<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Models\Media;
use App\Models\User;
use App\Support\StaffAccess;

final class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::isAdmin($user)
            || StaffAccess::can($user, StaffPermission::Translate);
    }

    public function view(User $user, Media $media): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Media $media): bool
    {
        return StaffAccess::isAdmin($user);
    }

    public function delete(User $user, Media $media): bool
    {
        return StaffAccess::isAdmin($user);
    }

    public function forceDelete(User $user, Media $media): bool
    {
        return false;
    }

    public function translate(User $user, Media $media): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }
}
