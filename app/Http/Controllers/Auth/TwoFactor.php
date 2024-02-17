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

class TwoFactor extends Controller
{
    public function verifyTwoFactor(Request $request)
    {
        $user = User::findOrFail($request->session()->get('login.user'));

        if ($user->two_factor_expires_at < now()) {
            auth()->logout();
            return response()->json([
                'message' => 'Verification successful',
                'redirect_to' => route('customers'),
            ]);
        }

        if ($request->input('two_factor_code') === $user->two_factor_code) {
            $request->session()->forget('login');
            auth()->login($user);
            $this->resetTwoFactor();

            return response()->json([
                'message' => 'Verification successful',
                'redirect_to' => route('catalog.channels.index'),
            ]);
        }

        return response()->json([
            'message' => 'Неверный код. Пожалуйста, попробуйте снова.',
        ], 422);
    }

    public function resetTwoFactor()
    {
        $user = auth()->user();

        if ($user->two_factor_enabled) {
            $user->resetTwoFactorCode();
            return response()->json([
                'message' => 'Verification successful',
                'redirect_to' => route('catalog.channels.index'),
            ]);
        }

        return response()->json([
            'message' => 'Reset successful',
            'redirect_to' => route('customers'),
        ]);
    }

    public function enableTwoFactor(Request $request)
    {
        $user = auth()->user();

        if (!$user->two_factor_enabled) {
            // Ensure the user has confirmed the password
            if (!Hash::check($request->input('password'), auth()->user()->password)) {
                throw ValidationException::withMessages([
                    'message' => ['Указанный пароль неверен.'],
                ]);
            }

            $user->update(['two_factor_enabled' => true]);

            $user->generateTwoFactorCode();
            $request->session()->invalidate();
            return response()->json([
                'message' => 'Успешно включена двухфакторная аутентификация',
            ]);
        }

        return response()->json([
            'message' => 'Двухфакторная аутентификация уже включена',
        ], 422);
    }

    public function disableTwoFactor(Request $request)
    {
        $user = auth()->user();

        if ($user->two_factor_enabled) {
            $user->update(['two_factor_enabled' => false]);
            $user->resetTwoFactorCode();

            return response()->json([
                'message' => 'Two-factor authentication disabled successfully',
            ]);
        }

        return response()->json([
            'message' => 'Two-factor authentication is not enabled',
        ], 422);
    }

    public function resendTwoFactor()
    {
        $user = auth()->user();

        if ($user->two_factor_enabled) {
            $user->generateTwoFactorCode();
            return response()->json('Код был отправлен снова');
        }

        return redirect()->route('customers');
    }
}
