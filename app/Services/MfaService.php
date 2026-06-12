<?php

namespace App\Services;

use App\Models\User;
use App\Models\MfaToken;
use App\Enums\MfaMethodEnum;
use App\Exceptions\MfaException;
use Illuminate\Support\Str;

class MfaService
{
    /**
     * Generate TOTP secret
     */
    public function generateTotpSecret(): string
    {
        return $this->user->generateTotpSecret();
    }

    /**
     * Enable TOTP
     */
    public function enableTotp(User $user): array
    {
        if ($user->hasMfaEnabled()) {
            throw MfaException::alreadyEnabled();
        }

        $secret = $user->generateTotpSecret();

        return [
            'secret' => $secret,
            'qrCode' => $this->generateQrCode($user->email, $secret),
        ];
    }

    /**
     * Verify and save TOTP
     */
    public function verifyAndSaveTotp(User $user, string $token): bool
    {
        if (!$user->verifyTotpToken($token)) {
            return false;
        }

        $secret = $user->generateTotpSecret();
        $user->mfaTokens()->create([
            'method' => MfaMethodEnum::TOTP->value,
            'token' => $secret,
            'verified_at' => now(),
        ]);

        return true;
    }

    /**
     * Send SMS code
     */
    public function sendSmsCode(User $user, string $phone): bool
    {
        if (!$phone) {
            throw MfaException::phoneNumberRequired();
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->mfaTokens()->create([
            'method' => MfaMethodEnum::SMS->value,
            'token' => hash('sha256', $code),
            'data' => ['phone' => $phone],
            'expires_at' => now()->addMinutes(5),
        ]);

        // TODO: Send SMS via Twilio or other provider
        // \Log::info("SMS Code for {$phone}: {$code}");

        return true;
    }

    /**
     * Verify SMS code
     */
    public function verifySmsCode(User $user, string $code): bool
    {
        $token = $user->mfaTokens()
            ->where('method', MfaMethodEnum::SMS->value)
            ->whereNull('verified_at')
            ->notExpired()
            ->first();

        if (!$token) {
            throw MfaException::tokenExpired();
        }

        if (hash('sha256', $code) !== $token->token) {
            throw MfaException::invalidToken();
        }

        $token->update(['verified_at' => now()]);
        return true;
    }

    /**
     * Send email code
     */
    public function sendEmailCode(User $user): bool
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->mfaTokens()->create([
            'method' => MfaMethodEnum::EMAIL->value,
            'token' => hash('sha256', $code),
            'expires_at' => now()->addMinutes(15),
        ]);

        // TODO: Send email
        // $user->notify(new MfaEmailCodeNotification($code));

        return true;
    }

    /**
     * Verify email code
     */
    public function verifyEmailCode(User $user, string $code): bool
    {
        $token = $user->mfaTokens()
            ->where('method', MfaMethodEnum::EMAIL->value)
            ->whereNull('verified_at')
            ->notExpired()
            ->first();

        if (!$token) {
            throw MfaException::tokenExpired();
        }

        if (hash('sha256', $code) !== $token->token) {
            throw MfaException::invalidToken();
        }

        $token->update(['verified_at' => now()]);
        return true;
    }

    /**
     * Generate backup codes
     */
    public function generateBackupCodes(User $user): array
    {
        $codes = $user->generateBackupCodes(10);
        $user->saveBackupCodes($codes);
        return $codes;
    }

    /**
     * Disable MFA
     */
    public function disableMfa(User $user, string $method): void
    {
        $user->mfaTokens()
            ->where('method', $method)
            ->delete();
    }

    /**
     * Disable all MFA
     */
    public function disableAllMfa(User $user): void
    {
        $user->mfaTokens()->delete();
    }

    /**
     * Generate QR code
     */
    private function generateQrCode(string $email, string $secret): string
    {
        $appName = config('app.name');
        $data = "otpauth://totp/{$appName}:{$email}?secret={$secret}&issuer={$appName}";
        // TODO: Generate actual QR code using qrcode library
        return $data;
    }
}
