<?php

namespace App\Enums;

enum MfaMethodEnum: string
{
    case TOTP = 'totp';
    case SMS = 'sms';
    case EMAIL = 'email';
    case BACKUP_CODES = 'backup_codes';

    public function label(): string
    {
        return match ($this) {
            self::TOTP => 'Time-based One-Time Password (Authenticator App)',
            self::SMS => 'SMS Text Message',
            self::EMAIL => 'Email Verification',
            self::BACKUP_CODES => 'Backup Codes',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::TOTP => 'Use an authenticator app like Google Authenticator or Authy',
            self::SMS => 'Receive a code via SMS to your registered phone number',
            self::EMAIL => 'Receive a code via email to your registered email address',
            self::BACKUP_CODES => 'Use pre-generated backup codes for emergency access',
        };
    }

    public function requiresPhoneNumber(): bool
    {
        return $this === self::SMS;
    }

    public function isBackupMethod(): bool
    {
        return $this === self::BACKUP_CODES;
    }
}
