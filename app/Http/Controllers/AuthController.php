<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Services\AuthenticationService;
use App\Services\MfaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        private AuthenticationService $authService,
        private MfaService $mfaService
    ) {}

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(LoginRequest $request)
    {
        $user = $this->authService->authenticate(
            $request->email,
            $request->password
        );

        if (!$user) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        Auth::login($user);

        if ($user->hasMfaEnabled()) {
            return redirect('/auth/mfa/verify');
        }

        return redirect('/dashboard');
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    /**
     * Show MFA setup
     */
    public function showMfaSetup()
    {
        return view('auth.mfa-setup');
    }

    /**
     * Setup TOTP
     */
    public function setupTotp()
    {
        $user = Auth::user();
        $data = $this->mfaService->enableTotp($user);

        return view('auth.totp-setup', $data);
    }

    /**
     * Verify and save TOTP
     */
    public function verifyTotp(Request $request)
    {
        $request->validate(['token' => 'required|string|size:6']);

        $user = Auth::user();
        if (!$this->mfaService->verifyAndSaveTotp($user, $request->token)) {
            return back()->withErrors(['token' => 'Invalid token']);
        }

        return redirect('/dashboard')->with('success', 'MFA enabled successfully');
    }

    /**
     * Show MFA verification
     */
    public function showMfaVerify()
    {
        return view('auth.verify-mfa');
    }

    /**
     * Verify MFA
     */
    public function verifyMfa(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $user = Auth::user();

        // Try TOTP
        if ($user->verifyTotpToken($request->token)) {
            session(['mfa_verified' => true]);
            return redirect('/dashboard');
        }

        // Try backup code
        if ($user->verifyBackupCode($request->token)) {
            session(['mfa_verified' => true]);
            return redirect('/dashboard');
        }

        return back()->withErrors(['token' => 'Invalid verification token']);
    }
}
