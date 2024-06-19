<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->userType == 'Admin') {
            return $next($request);
        }

        Alert::warning('401', 'Unauthorized Access.')
            ->autoClose(10000)
            ->timerProgressBar()
            ->showCloseButton();

        return redirect()->back();
    }
}
