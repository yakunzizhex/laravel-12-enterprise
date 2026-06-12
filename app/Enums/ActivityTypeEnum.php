<?php

namespace App\Enums;

enum ActivityTypeEnum: string
{
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case FAILED_LOGIN = 'failed_login';
    case MFA_ENABLED = 'mfa_enabled';
    case MFA_DISABLED = 'mfa_disabled';
    case PERMISSION_GRANTED = 'permission_granted';
    case PERMISSION_REVOKED = 'permission_revoked';
    case USER_CREATED = 'user_created';
    case USER_UPDATED = 'user_updated';
    case USER_DELETED = 'user_deleted';
    case ROLE_CREATED = 'role_created';
    case ROLE_UPDATED = 'role_updated';
    case ROLE_DELETED = 'role_deleted';
    case PASSWORD_CHANGED = 'password_changed';
    case EMAIL_CHANGED = 'email_changed';
    case SUSPICIOUS_ACTIVITY = 'suspicious_activity';
    case API_TOKEN_CREATED = 'api_token_created';
    case API_TOKEN_REVOKED = 'api_token_revoked';

    public function label(): string
    {
        return match ($this) {
            self::LOGIN => 'User Login',
            self::LOGOUT => 'User Logout',
            self::FAILED_LOGIN => 'Failed Login Attempt',
            self::MFA_ENABLED => 'MFA Enabled',
            self::MFA_DISABLED => 'MFA Disabled',
            self::PERMISSION_GRANTED => 'Permission Granted',
            self::PERMISSION_REVOKED => 'Permission Revoked',
            self::USER_CREATED => 'User Created',
            self::USER_UPDATED => 'User Updated',
            self::USER_DELETED => 'User Deleted',
            self::ROLE_CREATED => 'Role Created',
            self::ROLE_UPDATED => 'Role Updated',
            self::ROLE_DELETED => 'Role Deleted',
            self::PASSWORD_CHANGED => 'Password Changed',
            self::EMAIL_CHANGED => 'Email Changed',
            self::SUSPICIOUS_ACTIVITY => 'Suspicious Activity Detected',
            self::API_TOKEN_CREATED => 'API Token Created',
            self::API_TOKEN_REVOKED => 'API Token Revoked',
        };
    }

    public function severity(): string
    {
        return match ($this) {
            self::FAILED_LOGIN, self::SUSPICIOUS_ACTIVITY => 'high',
            self::MFA_DISABLED, self::PASSWORD_CHANGED, self::USER_DELETED => 'medium',
            default => 'low',
        };
    }
}
