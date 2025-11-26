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
        // Registrar middlewares personalizados
        $middleware->alias([
            'auth.check' => App\Http\Middleware\CheckAuth::class,
            'auth.guest' => App\Http\Middleware\RedirectIfAuthenticated::class,
            'ipsec' => \App\Http\Middleware\CheckIpsecTunnel::class,
        ]);

        // Middleware global para todas las rutas web
        $middleware->web(append: [
            // Aquí puedes agregar otros middlewares globales si necesitas
        ]);

        // Grupo de middleware 'web' (ya viene configurado por defecto)
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejo de excepciones
    })->create();
