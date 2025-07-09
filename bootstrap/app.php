<?php

use App\Http\Middleware\ApiExceptionHandler;
use App\Http\Middleware\CheckMonthlyApiLimit;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\LogApiRequest;
use App\Http\Middleware\LogApiRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);
        $middleware->validateCsrfTokens(
            except: [
                'telegram/**/webhook', 'mail-api/**', 'webhook/**', 'callback/**',
            ]
        );

        $middleware->prependToGroup('web', [
            \App\Http\Middleware\EncryptCookies::class,
            //            \Spatie\MailPreview\Http\Middleware\AddMailPreviewOverlayToResponse::class,
            //            SetLocale::class,
        ]);
        $middleware->group('api', [
            \App\Http\Middleware\AuthenticationWithQueryString::class,
            ForceJsonResponse::class,
            EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            SubstituteBindings::class,
            ApiExceptionHandler::class,
            LogApiRequests::class,
        ]);
        $middleware->alias([
            'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeCookieRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
            'laravelLocalizationRedirectFilter' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'api-limit' => CheckMonthlyApiLimit::class,
            'api-log' => LogApiRequest::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);

        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }

            return $request->expectsJson();
        });
    })->create();
