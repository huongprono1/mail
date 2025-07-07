<?php

namespace App\Http\Middleware;

use App\Http\Datas\ApiResponse;
use Closure;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler
{
    /**
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (Throwable $exception) {
            // Force JSON response for API routes
            if ($request->expectsJson() || $request->is('api/*')) {
                if ($exception instanceof ValidationException) {
                    return ApiResponse::error('Validation error', 422, $exception->errors());
                } elseif ($exception instanceof NotFoundHttpException) {
                    return ApiResponse::error('Resource not found', 404);
                } elseif ($exception instanceof MethodNotAllowedHttpException) {
                    return ApiResponse::error('Method not allowed', 405);
                } elseif ($exception instanceof ThrottleRequestsException) {
                    return ApiResponse::error('Rate limit exceeded. Try again later.', 429);
                }

                return ApiResponse::error($exception->getMessage(), method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);
            }

            throw $exception; // Let Laravel handle non-API requests
        }
    }
}
