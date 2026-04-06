<?php

require_once __DIR__ . '/../app/helpers.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // قبول الـ trailing slash — /quran/ تعمل زي /quran
            \Illuminate\Support\Facades\Route::redirect('/quran/', '/quran', 301);
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\SetTenant::class,
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\LogVisitor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 419 Page Expired → redirect to login with message
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            return redirect()->route('login')
                ->withErrors(['email' => 'انتهت صلاحية الجلسة، يرجى تسجيل الدخول مجدداً.']);
        });
    })->create();
