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
        // Trust all proxies (needed for Render.com reverse proxy)
        // so Laravel correctly detects HTTPS and generates correct asset URLs
        $middleware->trustProxies(at: '*');

        // Apply locale from session on every web request
        $middleware->appendToGroup('web', \App\Http\Middleware\SetLocale::class);

        // Custom aliases
        $middleware->alias([
            'check.for.price' => \App\Http\Middleware\CheckForPrice::class,
            'check.for.aut' => \App\Http\Middleware\CheckForAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
