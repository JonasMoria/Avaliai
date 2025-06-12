<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler {
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'status' => 405,
                    'message' => 'Método HTTP não permitido.',
                ], 405);
            }

            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Rota não encontrada.',
                ], 404);
            }

            return response()->json([
                'status' => 500,
                'message' => 'Erro interno no servidor.',
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
