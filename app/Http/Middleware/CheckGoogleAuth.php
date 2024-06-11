<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckGoogleAuth
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->provider == 'google') {
            return redirect()->route('home')->with('message', 'You are already logged in with a Google account.');
        }

        return $next($request);
    }
}

