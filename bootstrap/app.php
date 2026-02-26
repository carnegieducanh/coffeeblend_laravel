<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware (TrustProxies, HandleCors, ValidatePostSize,
        // TrimStrings, ConvertEmptyStringsToNull...) đã có sẵn trong Laravel 12.

        // Web group (EncryptCookies, StartSession, VerifyCsrfToken,
        // SubstituteBindings...) đã có sẵn trong Laravel 12.

        // API group (ThrottleRequests:api, SubstituteBindings)
        // đã có sẵn trong Laravel 12.

        // Các alias chuẩn (auth, auth.basic, can, guest, throttle,
        // verified, password.confirm, signed...) đã có sẵn trong Laravel 12.

        // Custom aliases
        $middleware->alias([
            'check.for.price' => \App\Http\Middleware\CheckForPrice::class,
            'check.for.aut' => \App\Http\Middleware\CheckForAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
