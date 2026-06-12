@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white rounded shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Login</h1>

    <form method="POST" action="/auth/login">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Password</label>
            <input type="password" name="password" required class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Login
        </button>
    </form>
</div>
@endsection
