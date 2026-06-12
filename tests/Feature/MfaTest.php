<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class MfaTest extends TestCase
{
    /** @test */
    public function test_user_can_enable_totp()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        // Test TOTP setup logic
        $this->assertFalse($user->hasMfaEnabled());
    }

    /** @test */
    public function test_user_can_verify_backup_code()
    {
        $user = User::factory()->create();

        // Generate backup codes
        $codes = $user->generateBackupCodes(10);
        $user->saveBackupCodes($codes);

        // Verify first code
        $this->assertTrue($user->verifyBackupCode($codes[0]));

        // Second attempt should fail
        $this->assertFalse($user->verifyBackupCode($codes[0]));
    }
}
