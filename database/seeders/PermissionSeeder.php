<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            PermissionEnum::USERS_VIEW,
            PermissionEnum::USERS_CREATE,
            PermissionEnum::USERS_EDIT,
            PermissionEnum::USERS_DELETE,
            PermissionEnum::ROLES_VIEW,
            PermissionEnum::ROLES_CREATE,
            PermissionEnum::ROLES_EDIT,
            PermissionEnum::ROLES_DELETE,
            PermissionEnum::PERMISSIONS_VIEW,
            PermissionEnum::PERMISSIONS_MANAGE,
            PermissionEnum::AUDIT_VIEW,
            PermissionEnum::SETTINGS_MANAGE,
            PermissionEnum::SECURITY_MANAGE,
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission->value],
                [
                    'name' => $permission->label(),
                    'group' => $permission->group(),
                    'is_active' => true,
                ]
            );
        }
    }
}
