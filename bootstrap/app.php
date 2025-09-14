<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('api/kiosk')
                ->middleware(['api'])
                ->group(base_path('routes/Api/kiosk.php'));

            Route::prefix('api/android')
                ->middleware(['api'])
                ->group(base_path('routes/Api/android.php'));

            Route::prefix('api/ios')
                ->middleware(['api'])
                ->group(base_path('routes/Api/ios.php'));

            Route::prefix('api/website')
                ->middleware(['api'])
                ->group(base_path('routes/Api/website.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
