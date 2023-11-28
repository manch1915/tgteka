<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Lockout;

class LoginController extends Controller
{

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
        return redirect()->route('catalog.channels.index');
    }

    protected function handleFailedLogin($request)
    {
        // If the login attempt was unsuccessful, increment the number of attempts
        RateLimiter::hit($this->throttleKey($request), 5 * 60);

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only( 'email','password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['status' => __($status)])
            : response()->json(['password' => [__($status)]],401);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
