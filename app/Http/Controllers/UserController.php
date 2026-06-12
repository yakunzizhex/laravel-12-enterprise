<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display dashboard
     */
    public function dashboard()
    {
        return view('dashboard.index');
    }

    /**
     * Show profile
     */
    public function showProfile()
    {
        return view('dashboard.profile', ['user' => auth()->user()]);
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . auth()->id(),
        ]);

        auth()->user()->update($request->only('name', 'phone'));

        return redirect('/dashboard/profile')->with('success', 'Profile updated');
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
        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully');
    }
}
