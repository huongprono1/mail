<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $response = $next($request);
        $endTime = microtime(true);
        $processingTimeMs = round(($endTime - $startTime) * 1000, 2);

        if (config('app.logging_api')) {
            $logData = [
                'timestamp' => now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
                'user_id' => Auth::check() ? Auth::id() : null, // Lấy ID user nếu đã đăng nhập
                'user_email' => Auth::check() && Auth::user()->email ? Auth::user()->email : null, // Lấy email user nếu có
                'request_body' => $request->all(), // Lấy toàn bộ body của request
                'response_status' => $response->getStatusCode(), // Lấy status code của response
                'execution_time_ms' => $processingTimeMs,
                // Bạn có thể thêm 'response_body' => $response->getContent() nếu muốn log cả nội dung response
            ];

            if ($response instanceof JsonResponse) {
                $responseData = $response->getData(true);
                if (is_array($responseData) && isset($responseData['success']) && $responseData['success'] === false) {
                    $logData['response_body'] = $responseData;
                }
            }

            defer(function () use ($logData) {
                if (! in_array($logData['response_status'], [200, 201])) {
                    \Log::channel('loki')->error($logData['url'], $logData);
                } else {
                    \Log::channel('loki')->info($logData['url'], $logData);
                }
            });
        }

        return $response;
    }
}
