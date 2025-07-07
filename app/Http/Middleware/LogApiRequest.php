<?php

namespace App\Http\Middleware;

use App\Models\ApiRequestLog;
use Closure;
use Illuminate\Http\Request;

class LogApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Start timing the request
        $startTime = microtime(true);

        // Process the request
        $response = $next($request);

        try {
            // Log the request and response
            ApiRequestLog::log($request, $response, $startTime);
        } catch (\Exception $e) {
            // Don't let logging errors break the application
            \Log::error('Failed to log API request: '.$e->getMessage(), [
                'exception' => $e,
            ]);
        }

        return $response;
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        // This method is called after the response is sent to the browser
    }
}
