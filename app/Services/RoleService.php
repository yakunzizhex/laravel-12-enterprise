<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Permission;

class RoleService
{
    /**
     * Create role
     */
    public function create(array $data): Role
    {
        return Role::create($data);
    }

    /**
     * Update role
     */
    public function update(Role $role, array $data): Role
    {
        $role->update($data);
        return $role;
    }

    /**
     * Delete role
     */
    public function delete(Role $role): bool
    {
        return $role->delete();
    }

    /**
     * Attach permissions
     */
    public function attachPermissions(Role $role, array $permissionSlugs): void
    {
        $permissions = Permission::whereIn('slug', $permissionSlugs)->pluck('id');
        $role->permissions()->syncWithoutDetaching($permissions);
    }

    /**
     * Detach permissions
     */
    public function detachPermissions(Role $role, array $permissionSlugs): void
    {
        $permissions = Permission::whereIn('slug', $permissionSlugs)->pluck('id');
        $role->permissions()->detach($permissions);
    }

    /**
     * Sync permissions
     */
    public function syncPermissions(Role $role, array $permissionSlugs): void
    {
        $role->syncPermissions($permissionSlugs);
    }

    /**
     * Get role with permissions
     */
    public function getWithPermissions(Role $role)
    {
        return $role->load('permissions');
    }

    /**
     * Get all active roles
     */
    public function getAllActive()
    {
        return Role::active()->get();
    }
}
