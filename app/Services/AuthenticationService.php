<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationService
{
    /**
     * Authenticate user
     */
    public function authenticate(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            $this->recordFailedAttempt($email);
            return null;
        }

        if (!$user->isActive()) {
            return null;
        }

        $this->recordSuccessfulLogin($user);
        return $user;
    }

    /**
     * Record successful login
     */
    public function recordSuccessfulLogin(User $user): void
    {
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);
    }

    /**
     * Record failed login attempt
     */
    public function recordFailedAttempt(string $email): void
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return;
        }

        $attempts = $user->failed_login_attempts + 1;
        $maxAttempts = config('auth.max_login_attempts', 5);

        $updateData = [
            'failed_login_attempts' => $attempts,
        ];

        if ($attempts >= $maxAttempts) {
            $lockoutDuration = config('auth.lockout_duration', 900); // 15 minutes
            $updateData['locked_until'] = now()->addSeconds($lockoutDuration);
        }

        $user->update($updateData);
    }

    /**
     * Unlock user account
     */
    public function unlockAccount(User $user): void
    {
        $user->update([
            'locked_until' => null,
            'failed_login_attempts' => 0,
        ]);
    }

    /**
     * Create API token
     */
    public function createApiToken(User $user, string $name = 'api-token', array $abilities = ['*']): string
    {
        return $user->createToken($name, $abilities)->plainTextToken;
    }

    /**
     * Revoke all tokens
     */
    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Verify email
     */
    public function verifyEmail(User $user): void
    {
        $user->update([
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(User $user, string $oldPassword, string $newPassword): bool
    {
        if (!Hash::check($oldPassword, $user->password)) {
            return false;
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return true;
    }

    /**
     * Reset password
     */
    public function resetPassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }
}
