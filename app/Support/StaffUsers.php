<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\StaffRole;
use App\Models\User;

final class StaffUsers
{
    public static function superAdminCount(): int
    {
        return User::query()->role(StaffRole::SuperAdmin->value)->count();
    }

    public static function isLastSuperAdmin(User $user): bool
    {
        return $user->hasRole(StaffRole::SuperAdmin->value)
            && self::superAdminCount() === 1;
    }

    public static function canAssign(User $actor, StaffRole $role): bool
    {
        if (! StaffAccess::isAdmin($actor)) {
            return false;
        }

        if ($role === StaffRole::SuperAdmin && ! $actor->hasRole(StaffRole::SuperAdmin->value)) {
            return false;
        }

        return true;
    }

    public static function label(StaffRole $role): string
    {
        return match ($role) {
            StaffRole::SuperAdmin => 'Super administrateur',
            StaffRole::Admin => 'Administrateur',
            StaffRole::ContentManager => 'Responsable contenu',
            StaffRole::TechnicalEditor => 'Rédacteur technique',
            StaffRole::CommunicationsEditor => 'Rédacteur communication',
            StaffRole::Translator => 'Traducteur',
            StaffRole::ReviewerPublisher => 'Relecteur / Publieur',
            StaffRole::SupportAgent => 'Agent support',
        };
    }
}
