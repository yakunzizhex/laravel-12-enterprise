<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@enterprise.local'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('SecurePassword123!'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->grantRole('super_admin');

        $user = User::updateOrCreate(
            ['email' => 'user@enterprise.local'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('SecurePassword123!'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $user->grantRole('user');
    }
}
