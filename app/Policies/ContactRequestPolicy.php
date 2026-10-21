<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StaffPermission;
use App\Models\ContactRequest;
use App\Models\User;
use App\Support\StaffAccess;

final class ContactRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return StaffAccess::can($user, StaffPermission::ContactInbox);
    }

    public function view(User $user, ContactRequest $contactRequest): bool
    {
        return StaffAccess::can($user, StaffPermission::ContactInbox);
    }

    public function update(User $user, ContactRequest $contactRequest): bool
    {
        return StaffAccess::can($user, StaffPermission::ContactInbox);
    }
}
