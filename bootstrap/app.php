<?php

use App\Http\Middleware\KeycloakAuthentication;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'users/*',
            'users',
        ]);
        $middleware->append(KeycloakAuthentication::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(ValidationException $e) {
            return response()->json([
                'type' => get_class($e),
                'message' => $e->getMessage(),
                'errors' => $e->validator->errors()
            ], 422);
        });

        $exceptions->render(function(NotFoundHttpException $e) {
            return response()->json([
                'type' => get_class($e),
                'message' => $e->getMessage(),
            ], 404);
        });

        $exceptions->render(function(ItemNotFoundException $e) {
            return response()->json([
                'type' => get_class($e),
                'message' => $e->getMessage(),
            ], 404);
        });

        $exceptions->render(function(Exception $e) {
            return response()->json([
                'type' => get_class($e),
                'message' => $e->getMessage(),
            ], 500);
        });

        $exceptions->render(function(Throwable $e) {
            return response()->json([
                'type' => get_class($e),
                'message' => $e->getMessage(),
            ], 500);
        });
    })->create();
