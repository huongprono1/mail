<?php

namespace App\Http\Middleware;

use App\Models\MonthlyApiUsage;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckMonthlyApiLimit
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Get or create the current month's usage record
        $usage = MonthlyApiUsage::getCurrentUsage($user);

        // Check if user has reached their monthly limit
        if ($usage->hasReachedLimit()) {
            return new JsonResponse([
                'message' => 'You have reached your monthly API request limit. Please try again next month or upgrade your plan.',
                'current_usage' => $usage->count,
                'limit' => $usage->getLimit(),
                'reset_date' => now()->endOfMonth()->toDateString(),
            ], 429);
        }

        // Increment the usage counter
        $usage->incrementUsage();

        // Add headers to the response
        $response = $next($request);

        if (method_exists($response, 'header')) {
            $response->headers->set('X-RateLimit-Limit', $usage->getLimit());
            $response->headers->set('X-RateLimit-Remaining', $usage->getRemainingRequests());
            $response->headers->set('X-RateLimit-Reset', now()->endOfMonth()->timestamp);
        }

        return $response;
    }
}
