<?php

namespace App\Providers;

use App\Http\Datas\ApiResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(\Illuminate\Contracts\Debug\ExceptionHandler::class, function ($app) {
            return new class($app) extends \Illuminate\Foundation\Exceptions\Handler {
                public function render($request, Throwable $exception)
                {
                    if ($request->expectsJson() || $request->is('api/*')) {
                        if ($exception instanceof ValidationException) {
                            return ApiResponse::error('Validation error', 422, $exception->errors());
                        } elseif ($exception instanceof NotFoundHttpException) {
                            return ApiResponse::error('Resource not found', 404);
                        } elseif ($exception instanceof MethodNotAllowedHttpException) {
                            return ApiResponse::error('Method not allowed', 405);
                        }

                        return ApiResponse::error($exception->getMessage(), method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);
                    }

                    return parent::render($request, $exception);
                }
            };
        });
    }

    public function boot(): void
    {
        //
    }
}
