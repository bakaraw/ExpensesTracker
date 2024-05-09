<?php

use App\Http\Middleware\GenerateSafeSubmitToken;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleSafeSubmit;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            GenerateSafeSubmitToken::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //s
    })->create();
