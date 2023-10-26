<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Lockout;

class LoginController extends Controller
{
    // Create a new controller instance.
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')).'|'.$request->ip();
    }

    protected function fireLockoutResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.throttle', [
                'seconds' => RateLimiter::availableIn($this->throttleKey($request)),
            ])],
        ])->status(429);
    }

    // Handle a login request to the application.
    public function login(Request $request)
    {
        $this->validateLoginAttempts($request);

        if (Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::clear($this->throttleKey($request));

            return $this->handleSuccessfulLogin($request);
        }

        return $this->handleFailedLogin($request);
    }

    protected function validateLoginAttempts($request)
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 3)) {
            event(new Lockout($request));
            $this->fireLockoutResponse($request);
        }
    }

    protected function handleSuccessfulLogin($request)
    {
        $request->session()->regenerate();
        return redirect()->intended('channels-catalog');
    }

    protected function handleFailedLogin($request)
    {
        // If the login attempt was unsuccessful, increment the number of attempts
        RateLimiter::hit($this->throttleKey($request), 1);

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
