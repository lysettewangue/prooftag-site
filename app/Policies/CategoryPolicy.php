<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Models\Category;
use App\Models\User;
use App\Support\StaffAccess;

final class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::isStaff($user);
    }

    public function view(User $user, Category $category): bool
    {
        return StaffAccess::isStaff($user);
    }

    public function create(User $user): bool
    {
        return StaffAccess::canUpdateCommunications($user);
    }

    public function update(User $user, Category $category): bool
    {
        return StaffAccess::canUpdateCommunications($user);
    }

    public function translate(User $user, Category $category): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }

    public function publish(User $user, Category $category): bool
    {
        return StaffAccess::can($user, StaffPermission::Publish);
    }
}
