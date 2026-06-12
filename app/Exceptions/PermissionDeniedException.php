<?php

namespace App\Exceptions;

use Exception;

class PermissionDeniedException extends Exception
{
    public static function forPermission(string $permission): self
    {
        return new self("You do not have the '{$permission}' permission.");
    }

    public static function forRole(string $role): self
    {
        return new self("You do not have the required '{$role}' role.");
    }

    public static function insufficientPrivileges(): self
    {
        return new self('Insufficient privileges to perform this action.');
    }

    public static function mfaRequired(): self
    {
        return new self('Multi-factor authentication is required to perform this action.');
    }

    public static function accountLocked(): self
    {
        return new self('Your account has been locked. Please contact an administrator.');
    }

    public static function suspiciousActivity(): self
    {
        return new self('Suspicious activity detected. Please verify your identity.');
    }
}
