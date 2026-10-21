<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Models\Product;
use App\Models\User;
use App\Support\StaffAccess;

final class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::isStaff($user);
    }

    public function view(User $user, Product $product): bool
    {
        return StaffAccess::isStaff($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Product $product): bool
    {
        return StaffAccess::canUpdateTechnical($user);
    }

    public function delete(User $user, Product $product): bool
    {
        return false;
    }

    public function translate(User $user, Product $product): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }

    public function publish(User $user, Product $product): bool
    {
        return StaffAccess::can($user, StaffPermission::Publish);
    }
}
