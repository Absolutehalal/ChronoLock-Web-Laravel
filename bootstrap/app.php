<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AuthenticateUser;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'admin' => \App\Http\Middleware\Admin::class,
            'faculty' => \App\Http\Middleware\Faculty::class,
            'student' => \App\Http\Middleware\Student::class,
            // 'technician' => \App\Http\Middleware\Technician::class,
            // 'lab-in-charge' => \App\Http\Middleware\LabInCharge::class,
            'profile' => \App\Http\Middleware\Profile::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
