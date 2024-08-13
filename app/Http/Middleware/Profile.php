<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class Profile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $id
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $id = null)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect('login'); // Redirect to login if not authenticated
        }

        // Retrieve the user ID from the route parameter
        $profileId = $request->route('id');

        // Ensure the user is authorized to access the profile
        if (Auth::id() != $profileId) {
            // abort(403, 'Unauthorized action.'); // Abort if not authorized
            Alert::info("Oops...", "Unauthorized action.")
                ->showCloseButton()
                ->timerProgressBar();

            return redirect()->back();
        }

        return $next($request);
    }
}
