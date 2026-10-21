<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\PageCode;
use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use App\Models\Page;
use App\Models\User;
use App\Support\StaffAccess;

final class PagePolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::canUpdateTechnical($user)
            || StaffAccess::canUpdateCommunications($user)
            || StaffAccess::can($user, StaffPermission::Translate)
            || $user->hasRole(StaffRole::ReviewerPublisher->value);
    }

    public function view(User $user, Page $page): bool
    {
        if ($this->isTechnicalHub($page)) {
            return StaffAccess::canUpdateTechnical($user)
                || StaffAccess::can($user, StaffPermission::Translate)
                || $user->hasRole(StaffRole::ReviewerPublisher->value);
        }

        return StaffAccess::canUpdateCommunications($user)
            || StaffAccess::can($user, StaffPermission::Translate)
            || $user->hasRole(StaffRole::ReviewerPublisher->value);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Page $page): bool
    {
        if ($this->isTechnicalHub($page)) {
            return StaffAccess::canUpdateTechnical($user);
        }

        return StaffAccess::canUpdateCommunications($user);
    }

    public function delete(User $user, Page $page): bool
    {
        return false;
    }

    public function translate(User $user, Page $page): bool
    {
        return StaffAccess::can($user, StaffPermission::Translate);
    }

    public function publish(User $user, Page $page): bool
    {
        if ($this->isTechnicalHub($page)) {
            return StaffAccess::isAdmin($user)
                || $user->hasRole(StaffRole::ReviewerPublisher->value);
        }

        return StaffAccess::isAdmin($user)
            || $user->hasRole(StaffRole::ReviewerPublisher->value)
            || StaffAccess::can($user, StaffPermission::Publish);
    }

    private function isTechnicalHub(Page $page): bool
    {
        return $page->code === PageCode::TechnicalInspection;
    }
}
