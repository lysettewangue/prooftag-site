<?php

declare(strict_types=1);

namespace App\Actions\Staff;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\StaffRole;
use App\Models\User;
use App\Support\StaffUsers;
use Illuminate\Support\Facades\Gate;

final class UpdateStaffUser
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $staff, User $actor, array $data): User
    {
        Gate::forUser($actor)->authorize('update', $staff);

        if (array_key_exists('name', $data)) {
            $name = trim((string) $data['name']);

            if ($name === '') {
                throw new DomainRuleViolation('Staff name is required.');
            }

            $staff->name = $name;
        }

        if (array_key_exists('email', $data)) {
            $email = strtolower(trim((string) $data['email']));

            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                throw new DomainRuleViolation('Staff email must be valid.');
            }

            if (User::query()->where('email', $email)->where('id', '!=', $staff->id)->exists()) {
                throw new DomainRuleViolation('Staff email must be unique.');
            }

            $staff->email = $email;
        }

        if (array_key_exists('password', $data) && filled($data['password'])) {
            $password = (string) $data['password'];

            if (strlen($password) < 8) {
                throw new DomainRuleViolation('Staff password must be at least 8 characters.');
            }

            $staff->password = $password;
        }

        $staff->save();

        if (array_key_exists('role', $data) && filled($data['role'])) {
            $role = $this->role($data['role']);

            if (! StaffUsers::canAssign($actor, $role)) {
                throw new DomainRuleViolation('This role cannot be assigned by the current user.');
            }

            if (StaffUsers::isLastSuperAdmin($staff) && $role !== StaffRole::SuperAdmin) {
                throw new DomainRuleViolation('The last Super Administrator cannot be demoted.');
            }

            $staff->assignStaffRole($role);
        }

        return $staff->refresh();
    }

    private function role(mixed $value): StaffRole
    {
        try {
            return StaffRole::from((string) $value);
        } catch (\ValueError) {
            throw new DomainRuleViolation('Staff role must be one of the eight frozen roles.');
        }
    }
}
