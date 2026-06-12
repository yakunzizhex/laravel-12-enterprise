<?php

namespace App\Traits;

use App\Models\MfaToken;
use App\Enums\MfaMethodEnum;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Str;

trait MultiFactorAuthenticatable
{
    /**
     * Get all MFA methods enabled for this user
     */
    public function getMfaMethods()
    {
        return $this->mfaTokens()
            ->distinct('method')
            ->pluck('method')
            ->toArray();
    }

    /**
     * Check if user has MFA enabled
     */
    public function hasMfaEnabled(): bool
    {
        return $this->mfaTokens()->exists();
    }

    /**
     * Generate TOTP secret
     */
    public function generateTotpSecret(): string
    {
        $google2fa = new Google2FA();
        return $google2fa->generateSecretKey();
    }

    /**
     * Verify TOTP token
     */
    public function verifyTotpToken(string $token): bool
    {
        $google2fa = new Google2FA();
        $secret = $this->getTotpSecret();

        if (!$secret) {
            return false;
        }

        return $google2fa->verifyKey($secret, $token, 1);
    }

    /**
     * Get TOTP secret
     */
    public function getTotpSecret(): ?string
    {
        return $this->mfaTokens()
            ->where('method', MfaMethodEnum::TOTP->value)
            ->first()?->token;
    }

    /**
     * Generate backup codes
     */
    public function generateBackupCodes(int $count = 10): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = Str::random(12);
        }
        return $codes;
    }

    /**
     * Save backup codes
     */
    public function saveBackupCodes(array $codes): void
    {
        $this->mfaTokens()
            ->where('method', MfaMethodEnum::BACKUP_CODES->value)
            ->delete();

        foreach ($codes as $code) {
            $this->mfaTokens()->create([
                'method' => MfaMethodEnum::BACKUP_CODES->value,
                'token' => hash('sha256', $code),
                'data' => ['code' => $code],
                'verified_at' => now(),
                'expires_at' => now()->addYear(),
            ]);
        }
    }

    /**
     * Verify backup code
     */
    public function verifyBackupCode(string $code): bool
    {
        $token = $this->mfaTokens()
            ->where('method', MfaMethodEnum::BACKUP_CODES->value)
            ->where('token', hash('sha256', $code))
            ->first();

        if ($token) {
            $token->delete();
            return true;
        }

        return false;
    }

    /**
     * Relationship: MFA Tokens
     */
    public function mfaTokens()
    {
        return $this->hasMany(MfaToken::class);
    }
}
