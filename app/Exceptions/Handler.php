<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function(ValidationException $e) {
            return response()->json([
                'type' => get_class($e),
                'message' => $e->getMessage(),
                'errors' => $e->validator->errors()
            ], 422);
        });

        $this->renderable(function(NotFoundHttpException $e) {
            return response()->json([
                'type' => get_class($e),
                'message' => $e->getMessage(),
            ], 404);
        });

        $this->renderable(function(ItemNotFoundException $e) {
            return response()->json([
                'type' => get_class($e),
                'message' => $e->getMessage(),
            ], 404);
        });

        $this->renderable(function(Exception $e) {
            return response()->json([
                'type' => get_class($e),
                'message' => $e->getMessage(),
            ], 500);
        });

        $this->renderable(function(Throwable $e) {
            return response()->json([
                'type' => get_class($e),
                'message' => $e->getMessage(),
            ], 500);
        });
    }
}