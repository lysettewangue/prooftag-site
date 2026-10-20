<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\StaffPermission;
use App\Enums\StaffRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public const GUARD = 'web';

    /**
     * Shield resource abilities for Spatie's Role model (pascal + colon).
     * Super Admin also receives these; other roles do not.
     *
     * @var list<string>
     */
    public const ROLE_RESOURCE_PERMISSIONS = [
        'ViewAny:Role',
        'View:Role',
        'Create:Role',
        'Update:Role',
        'Delete:Role',
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (StaffPermission::codes() as $name) {
            Permission::findOrCreate($name, self::GUARD);
        }

        foreach (self::ROLE_RESOURCE_PERMISSIONS as $name) {
            Permission::findOrCreate($name, self::GUARD);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (StaffRole::cases() as $staffRole) {
            $role = Role::findOrCreate($staffRole->value, self::GUARD);
            $permissions = $staffRole->permissionNames();

            if ($staffRole === StaffRole::SuperAdmin) {
                $permissions = array_merge($permissions, self::ROLE_RESOURCE_PERMISSIONS);
            }

            $role->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
