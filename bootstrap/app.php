<?php

// ─────────────────────────────────────────────────────────────────────────────
//  bootstrap/app.php  (Laravel 12)
//  Daftarkan middleware alias di sini
// ─────────────────────────────────────────────────────────────────────────────

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
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
        // Daftarkan alias middleware
        $middleware->alias([
            'admin'     => AdminMiddleware::class,
            'user.role' => UserMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
