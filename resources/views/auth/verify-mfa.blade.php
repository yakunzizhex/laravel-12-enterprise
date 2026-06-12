@extends('layouts.app')

@section('title', 'Verify MFA')

@section('content')
<div class="max-w-md mx-auto bg-white rounded shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Verify Multi-Factor Authentication</h1>

    <form method="POST" action="/auth/mfa/verify">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">Enter verification code</label>
            <input type="text" name="token" placeholder="000000" maxlength="6" required 
                   class="w-full border rounded px-3 py-2 text-center text-2xl tracking-widest">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Verify
        </button>
    </form>
</div>
@endsection
