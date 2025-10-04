<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\EnsureUserIsHomestaurant;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Create a route‑middleware alias called “homestaurant”
        $middleware->alias([
            'homestaurant' => EnsureUserIsHomestaurant::class,
            'ensure_homestaurant_approved' => App\Http\Middleware\EnsureHomestaurantApproved::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
