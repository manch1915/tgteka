<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectAfterLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check() && $slug = session('remembered_channel_slug')) {
            session()->forget('remembered_channel_slug');
            return redirect()->to(route('catalog.channels.show', $slug));
        }

        return $response;
    }
}
