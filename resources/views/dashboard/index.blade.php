@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded shadow p-6">
        <h3 class="font-semibold text-gray-600 text-sm uppercase">MFA Status</h3>
        <p class="text-2xl font-bold">
            @if (auth()->user()->hasMfaEnabled())
                <span class="text-green-600">Enabled</span>
            @else
                <span class="text-red-600">Disabled</span>
            @endif
        </p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="font-semibold text-gray-600 text-sm uppercase">Last Login</h3>
        <p class="text-lg">{{ auth()->user()->last_login_at?->diffForHumans() ?? 'Never' }}</p>
    </div>
    <div class="bg-white rounded shadow p-6">
        <h3 class="font-semibold text-gray-600 text-sm uppercase">Roles</h3>
        <p class="text-lg">{{ auth()->user()->roles->count() }}</p>
    </div>
</div>

<div class="bg-white rounded shadow p-6">
    <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
    <div class="space-y-2">
        <a href="/profile" class="block text-blue-600 hover:text-blue-900">→ Edit Profile</a>
        <a href="/mfa/setup" class="block text-blue-600 hover:text-blue-900">→ Setup MFA</a>
    </div>
</div>
@endsection
