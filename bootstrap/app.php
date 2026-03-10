<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'business' => \App\Http\Middleware\CheckBusinessAccess::class,
            'super-admin' => \App\Http\Middleware\CheckSuperAdmin::class,
            'admin' => \App\Http\Middleware\CheckAdmin::class,
            'plan.feature' => \App\Http\Middleware\CheckPlanFeature::class,
            'plan.limit' => \App\Http\Middleware\CheckPlanLimit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Show detailed errors for super-admin users (except validation errors)
        $exceptions->render(function (\Throwable $e, $request) {
            // Don't catch validation exceptions - let Laravel handle redirect back
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return null;
            }
            if ($request->user()?->role === 'super_admin' && !app()->hasDebugModeEnabled()) {
                return response()->view('errors.debug', [
                    'exception' => $e,
                ], 500);
            }
        });
    })->create();
