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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $id
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $id = null): Response
    {
        // Check if the user is authenticated and is an Admin
        if (Auth::check() && Auth::user()->userType == 'Admin') {
            return $next($request); // Allow the request to proceed
        }

        if (Auth::check()) {

            $userType = Auth::user()->userType;

            $allowedRoutes = [
                'index',
                'adminScheduleManagement',
                'getSchedules',
                'createSchedule',
                'createRegularSchedule',
                'editMakeUpSchedule',
                'updateMakeUpSchedule',
                'editRegularSchedule',
                'updateRegularSchedule',
                'deleteRegularSchedule',
                'deleteMakeUpSchedule',
                'schedule.import',
                'exportPDF',
                'previewPDF',
                'logs'
            ]; // Add the route names you want to allow

        
            if (($userType == 'Technician' || $userType == 'Lab-in-Charge') &&
                in_array($request->route()->getName(), $allowedRoutes)
            ) {
                return $next($request); // Allow only specific routes for Technician and Lab-in-Charge
            }
        }


        // If not, show unauthorized access alert
        Alert::warning('401', 'Unauthorized Access.')
            ->autoClose(10000)
            ->timerProgressBar()
            ->showCloseButton();


        return redirect()->back();
    }
}
