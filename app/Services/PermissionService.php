<?php

namespace App\Services;

use App\Models\Permission;

class PermissionService
{
    /**
     * Create permission
     */
    public function create(array $data): Permission
    {
        return Permission::create($data);
    }

    /**
     * Update permission
     */
    public function update(Permission $permission, array $data): Permission
    {
        $permission->update($data);
        return $permission;
    }

    /**
     * Delete permission
     */
    public function delete(Permission $permission): bool
    {
        return $permission->delete();
    }

    /**
     * Get permissions by group
     */
    public function getByGroup(string $group)
    {
        return Permission::byGroup($group)->active()->get();
    }

    /**
     * Get all groups
     */
    public function getAllGroups()
    {
        return Permission::distinct('group')->pluck('group')->filter();
    }

    /**
     * Get grouped permissions
     */
    public function getGrouped()
    {
        return Permission::active()
            ->get()
            ->groupBy('group');
    }

    /**
     * Get all active permissions
     */
    public function getAllActive()
    {
        return Permission::active()->get();
    }
}
