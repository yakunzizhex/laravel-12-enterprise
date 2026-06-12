<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case USERS_VIEW = 'users.view';
    case USERS_CREATE = 'users.create';
    case USERS_EDIT = 'users.edit';
    case USERS_DELETE = 'users.delete';
    case ROLES_VIEW = 'roles.view';
    case ROLES_CREATE = 'roles.create';
    case ROLES_EDIT = 'roles.edit';
    case ROLES_DELETE = 'roles.delete';
    case PERMISSIONS_VIEW = 'permissions.view';
    case PERMISSIONS_MANAGE = 'permissions.manage';
    case AUDIT_VIEW = 'audit.view';
    case SETTINGS_MANAGE = 'settings.manage';
    case SECURITY_MANAGE = 'security.manage';

    public function label(): string
    {
        return match ($this) {
            self::USERS_VIEW => 'View Users',
            self::USERS_CREATE => 'Create Users',
            self::USERS_EDIT => 'Edit Users',
            self::USERS_DELETE => 'Delete Users',
            self::ROLES_VIEW => 'View Roles',
            self::ROLES_CREATE => 'Create Roles',
            self::ROLES_EDIT => 'Edit Roles',
            self::ROLES_DELETE => 'Delete Roles',
            self::PERMISSIONS_VIEW => 'View Permissions',
            self::PERMISSIONS_MANAGE => 'Manage Permissions',
            self::AUDIT_VIEW => 'View Audit Logs',
            self::SETTINGS_MANAGE => 'Manage Settings',
            self::SECURITY_MANAGE => 'Manage Security',
        };
    }

    public function group(): string
    {
        return match ($this) {
            self::USERS_VIEW, self::USERS_CREATE, self::USERS_EDIT, self::USERS_DELETE => 'User Management',
            self::ROLES_VIEW, self::ROLES_CREATE, self::ROLES_EDIT, self::ROLES_DELETE => 'Role Management',
            self::PERMISSIONS_VIEW, self::PERMISSIONS_MANAGE => 'Permission Management',
            self::AUDIT_VIEW => 'Audit Management',
            self::SETTINGS_MANAGE => 'Settings',
            self::SECURITY_MANAGE => 'Security',
        };
    }
}
