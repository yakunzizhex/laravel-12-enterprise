<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    /**
     * Get all users
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();

        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%");
        }

        if ($request->has('role')) {
            $query->byRole($request->role);
        }

        $users = $query->with('roles', 'permissions')
            ->paginate($request->get('per_page', 15));

        return $this->success(
            UserResource::collection($users)
        );
    }

    /**
     * Create user
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            foreach ($request->roles as $roleSlug) {
                $user->grantRole($roleSlug);
            }
        }

        return $this->success(
            new UserResource($user),
            'User created successfully',
            201
        );
    }

    /**
     * Get user
     */
    public function show(User $user)
    {
        return $this->success(
            new UserResource($user->load('roles', 'permissions'))
        );
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'is_active' => 'boolean',
        ]);

        $user->update($request->only('name', 'email', 'phone', 'is_active'));

        return $this->success(
            new UserResource($user),
            'User updated successfully'
        );
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->success(
            null,
            'User deleted successfully'
        );
    }
}
