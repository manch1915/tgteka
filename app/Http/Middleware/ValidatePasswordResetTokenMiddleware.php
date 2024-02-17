<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ValidatePasswordResetTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user === null) {
            return redirect()->route('customers')->withErrors(['email' => 'Пользователь с этим email не найден.']);
        }

        $token = $request->route('token');

        if (!Password::broker()->tokenExists($user, $token)) {
            return redirect()->route('password.reset')->withErrors(['token' => 'Недопустимый токен']);
        }

        return $next($request);
    }
}
