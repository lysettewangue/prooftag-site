<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use App\Models\Category;
use App\Models\User;
use App\Support\StaffAccess;

final class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::canUpdateCommunications($user)
            || StaffAccess::can($user, StaffPermission::Translate)
            || $user->hasRole(StaffRole::ReviewerPublisher->value);
    }

    public function view(User $user, Category $category): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return StaffAccess::canUpdateCommunications($user);
    }

    public function update(User $user, Category $category): bool
    {
        return StaffAccess::canUpdateCommunications($user);
    }

    public function delete(User $user, Category $category): bool
    {
        return false;
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return false;
    }

    public function translate(User $user, Category $category): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }

    public function publish(User $user, Category $category): bool
    {
        return StaffAccess::isAdmin($user)
            || $user->hasRole(StaffRole::ReviewerPublisher->value)
            || StaffAccess::can($user, StaffPermission::Publish);
    }
}
