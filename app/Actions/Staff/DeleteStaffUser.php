<?php

declare(strict_types=1);

namespace App\Actions\Staff;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Models\User;
use App\Support\StaffUsers;
use Illuminate\Support\Facades\Gate;

final class DeleteStaffUser
{
    public function handle(User $staff, User $actor): void
    {
        Gate::forUser($actor)->authorize('delete', $staff);

        if (StaffUsers::isLastSuperAdmin($staff)) {
            throw new DomainRuleViolation('The last Super Administrator cannot be deleted.');
        }

        if ($actor->is($staff)) {
            throw new DomainRuleViolation('Staff cannot delete their own account.');
        }

        $staff->delete();
    }
}
