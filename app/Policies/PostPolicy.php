<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Models\Post;
use App\Models\User;
use App\Support\StaffAccess;

final class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::isStaff($user);
    }

    public function view(User $user, Post $post): bool
    {
        return StaffAccess::isStaff($user);
    }

    public function create(User $user): bool
    {
        return StaffAccess::canUpdateCommunications($user);
    }

    public function update(User $user, Post $post): bool
    {
        return StaffAccess::canUpdateCommunications($user);
    }

    public function delete(User $user, Post $post): bool
    {
        return StaffAccess::canUpdateCommunications($user);
    }

    public function translate(User $user, Post $post): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }

    public function publish(User $user, Post $post): bool
    {
        return StaffAccess::can($user, StaffPermission::Publish);
    }
}
