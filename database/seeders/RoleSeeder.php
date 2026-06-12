<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => RoleEnum::SUPER_ADMIN->label(),
                'slug' => RoleEnum::SUPER_ADMIN->value,
                'description' => RoleEnum::SUPER_ADMIN->description(),
                'permissions' => Permission::pluck('id')->toArray(),
            ],
            [
                'name' => RoleEnum::ADMIN->label(),
                'slug' => RoleEnum::ADMIN->value,
                'description' => RoleEnum::ADMIN->description(),
                'permissions' => Permission::whereIn('group', [
                    'User Management',
                    'Role Management',
                    'Audit Management',
                ])->pluck('id')->toArray(),
            ],
            [
                'name' => RoleEnum::MANAGER->label(),
                'slug' => RoleEnum::MANAGER->value,
                'description' => RoleEnum::MANAGER->description(),
                'permissions' => Permission::whereIn('slug', [
                    'users.view',
                    'users.edit',
                    'audit.view',
                ])->pluck('id')->toArray(),
            ],
            [
                'name' => RoleEnum::USER->label(),
                'slug' => RoleEnum::USER->value,
                'description' => RoleEnum::USER->description(),
                'permissions' => [],
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );

            $role->permissions()->sync($permissions);
        }
    }
}
