<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Session-based authentication for the web admin panel (separate from the
 * Sanctum token API in App\Http\Controllers\Api\Auth\AuthController).
 */
class LoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Accept either an email address or a username in the same field.
        $field = filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$field => $data['email'], 'password' => $data['password']], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Block inactive/suspended accounts.
        if (($request->user()->status ?? 'active') !== 'active') {
            Auth::guard('web')->logout();
            throw ValidationException::withMessages([
                'email' => 'Your account is not active. Please contact an administrator.',
            ]);
        }

        $request->user()->forceFill([
            'last_login_at' => now(),
            'total_logins' => (int) ($request->user()->total_logins ?? 0) + 1,
        ])->save();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
