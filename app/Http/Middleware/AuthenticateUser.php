<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/loginPage')->withErrors(['error' => 'You must be logged in to access this page.']);
        }

        return $next($request);
    }
}

