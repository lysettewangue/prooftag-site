<?php

declare(strict_types=1);

namespace App\Actions\Staff;

use App\Domain\Exceptions\DomainRuleViolation;
use App\Enums\StaffRole;
use App\Models\User;
use App\Support\StaffUsers;
use Illuminate\Support\Facades\Gate;

final class CreateStaffUser
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $actor, array $data): User
    {
        Gate::forUser($actor)->authorize('create', User::class);

        $role = $this->role($data['role'] ?? null);

        if (! StaffUsers::canAssign($actor, $role)) {
            throw new DomainRuleViolation('This role cannot be assigned by the current user.');
        }

        $name = trim((string) ($data['name'] ?? ''));
        $email = strtolower(trim((string) ($data['email'] ?? '')));
        $password = (string) ($data['password'] ?? '');

        if ($name === '') {
            throw new DomainRuleViolation('Staff name is required.');
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new DomainRuleViolation('Staff email must be valid.');
        }

        if (User::query()->where('email', $email)->exists()) {
            throw new DomainRuleViolation('Staff email must be unique.');
        }

        if (strlen($password) < 8) {
            throw new DomainRuleViolation('Staff password must be at least 8 characters.');
        }

        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $user->assignStaffRole($role);

        return $user->refresh();
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
