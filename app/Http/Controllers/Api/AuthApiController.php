<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthenticationService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function __construct(
        private AuthenticationService $authService
    ) {}

    /**
     * Register new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $this->authService->createApiToken($user);

        return $this->success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'User registered successfully', 201);
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request)
    {
        $user = $this->authService->authenticate(
            $request->email,
            $request->password
        );

        if (!$user) {
            return $this->error('Invalid credentials', 401);
        }

        $token = $this->authService->createApiToken($user);

        return $this->success([
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    /**
     * Get current user
     */
    public function me()
    {
        return $this->success(
            new UserResource(auth()->user())
        );
    }

    /**
     * Logout user
     */
    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return $this->success(null, 'Logged out successfully');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $this->authService->changePassword($user, $request->old_password, $request->password);

        return $this->success(null, 'Password changed successfully');
    }

    /**
     * Refresh token
     */
    public function refresh()
    {
        $user = auth()->user();
        $this->authService->revokeAllTokens($user);
        $token = $this->authService->createApiToken($user);

        return $this->success([
            'token' => $token,
        ]);
    }
}
