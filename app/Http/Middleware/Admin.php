<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class Admin
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
        // Check if the user is authenticated
        if (Auth::check()) {
            $userType = Auth::user()->userType;

            // If the user is an Admin, allow the request to proceed
            if ($userType == 'Admin') {
                return $next($request);
            }

            // Allowed routes for Technician or Lab-in-Charge
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
            ];

            // Allow specific routes for Technician and Lab-in-Charge
            if (in_array($userType, ['Technician', 'Lab-in-Charge']) && in_array($request->route()->getName(), $allowedRoutes)) {
                return $next($request);
            }
        }

        // If the user is not authorized, show an unauthorized access alert
        Alert::warning('401', 'Unauthorized Access.')
            ->autoClose(5000)
            ->timerProgressBar()
            ->showCloseButton();

        // Redirect back to the previous page
        return redirect()->back();
    }
}
