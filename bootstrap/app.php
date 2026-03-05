<?php

use App\Http\Middleware\EnforceCanonicalDomain;
use App\Http\Middleware\SetSiteLocale;
use App\Http\Middleware\CaptureAttribution;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'site-locale' => SetSiteLocale::class,
        ]);

        $middleware->web(append: [
            EnforceCanonicalDomain::class,
            CaptureAttribution::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
