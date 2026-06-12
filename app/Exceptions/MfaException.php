<?php

namespace App\Exceptions;

use Exception;

class MfaException extends Exception
{
    public static function tokenExpired(): self
    {
        return new self('MFA token has expired. Please request a new one.');
    }

    public static function invalidToken(): self
    {
        return new self('Invalid MFA token provided.');
    }

    public static function methodNotSupported(string $method): self
    {
        return new self("MFA method '{$method}' is not supported.");
    }

    public static function alreadyEnabled(): self
    {
        return new self('Multi-factor authentication is already enabled for this user.');
    }

    public static function notEnabled(): self
    {
        return new self('Multi-factor authentication is not enabled for this user.');
    }

    public static function phoneNumberRequired(): self
    {
        return new self('Phone number is required for SMS verification method.');
    }

    public static function noBackupCodes(): self
    {
        return new self('No backup codes available. Please regenerate them.');
    }
}
