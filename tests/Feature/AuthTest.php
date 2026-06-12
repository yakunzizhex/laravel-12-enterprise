<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /** @test */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function test_login_with_invalid_credentials()
    {
        $response = $this->post('/auth/login', [
            'email' => 'invalid@test.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }
}
