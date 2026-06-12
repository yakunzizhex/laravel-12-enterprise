<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case USER = 'user';
    case GUEST = 'guest';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Administrator',
            self::ADMIN => 'Administrator',
            self::MANAGER => 'Manager',
            self::USER => 'User',
            self::GUEST => 'Guest',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Full system access with all permissions',
            self::ADMIN => 'Administrative access with most permissions',
            self::MANAGER => 'Management access with team oversight',
            self::USER => 'Standard user access',
            self::GUEST => 'Limited read-only access',
        };
    }
}
