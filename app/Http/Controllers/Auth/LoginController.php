<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Lockout;

class LoginController extends Controller
{
    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard(): StatefulGuard
    {
        return Auth::guard();
    }

    // Create a new controller instance.
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Handle a login request to the application.
    public function login(LoginRequest $request)
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), $maxAttempts = 5, $decayMinutes = 1)) {
            event(new Lockout($request));

            $this->fireLockoutResponse($request);
        }

        $credentials = $request->only('email', 'password');

        if ($this->guard()->attempt($credentials)) {
            RateLimiter::clear($this->throttleKey($request));

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful, increment the number of attempts
        RateLimiter::hit($this->throttleKey($request), $decayMinutes);

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
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

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
