<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function test_user_can_view_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function test_unauthenticated_user_cannot_view_dashboard()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function test_user_has_roles()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $user->roles()->attach($role);

        $this->assertTrue($user->hasRole($role->slug));
    }
}
