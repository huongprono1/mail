<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Mcamara\LaravelLocalization\Exceptions\SupportedLocalesNotDefined;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws SupportedLocalesNotDefined
     */
    public function handle(Request $request, Closure $next): Response
    {
        //        $locale = $request->cookie('locale', null);
        $locale = $request->segment(1);

        if (! in_array($locale, array_keys(LaravelLocalization::getSupportedLocales()))) {
            $locale = LaravelLocalization::getDefaultLocale();
        }
        LaravelLocalization::setLocale($locale);
        App::setLocale($locale);

        return $next($request);
    }
}
