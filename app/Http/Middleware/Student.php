<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
class Student
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|null  $id
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $userType): Response
    {
        if (Auth::check()) {
            $userType = Auth::user()->userType;

            // If the user is an Student, allow the request to proceed
            if ($userType == 'Student') {
                return $next($request);
            }
        }
        Alert::warning('401', 'Unauthorized Access.')
            ->autoClose(5000)
            ->timerProgressBar()
            ->showCloseButton();

        return redirect()->back();
    }
}