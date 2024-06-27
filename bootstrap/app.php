<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Rutas API deben estar aquí
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Ejemplo de registro de middleware global
        // $middleware->push('auth:sanctum');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configuración de manejo de excepciones personalizado si es necesario
    })
    ->create();

