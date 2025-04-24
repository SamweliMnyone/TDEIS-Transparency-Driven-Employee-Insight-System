<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register global middleware
        $middleware->use([
            // e.g. \App\Http\Middleware\TrimStrings::class,
        ]);

        // Register route middleware using alias
        $middleware->alias([
            'auth.user' => App\Http\Middleware\AuthUser::class,
            'guest.user' => App\Http\Middleware\GuestUser::class,
            'session.timeout' => App\Http\Middleware\SessionTimeout::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'no-cache' => \App\Http\Middleware\NoCache::class, // Add this line
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);

    })


    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

