<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Check if the user is in the process of two-factor verification
        if ($user && $user->two_factor_enabled && $request->session()->has('login.user')) {
            // Allow access to the specified routes for verification
            if ($request->routeIs(['two-factor.index', 'two-factor.verify'])) {
                return $next($request);
            }

            // Redirect to the verification page if not on the allowed routes
            return redirect()->route('two-factor.index');
        }

        // Continue to the next middleware if not in the verification process
        return $next($request);
    }
}
