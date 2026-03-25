<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handle authentication exceptions for API routes
        if ($exception instanceof AuthenticationException && $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please provide a valid API token.',
                'error' => 'token_invalid'
            ], 401);
        }

        // Handle authorization exceptions for API routes
        if ($exception instanceof HttpException && $exception->getStatusCode() === 403 && $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You do not have permission to access this resource.',
                'error' => 'access_denied'
            ], 403);
        }

        return parent::render($request, $exception);
    }
}
