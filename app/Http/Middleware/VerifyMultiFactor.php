<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Exceptions\PermissionDeniedException;
use Illuminate\Support\Facades\Auth;

class VerifyMultiFactor
{
    /**
     * Handle incoming request
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Check if MFA is required
        if (config('auth.mfa.required') && !$user->hasMfaEnabled()) {
            return redirect('/auth/mfa/setup');
        }

        // Check if user has been verified via MFA in this session
        if ($user->hasMfaEnabled() && !session('mfa_verified')) {
            return redirect('/auth/mfa/verify');
        }

        return $next($request);
    }
}
