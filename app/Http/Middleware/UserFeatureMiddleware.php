<?php

namespace App\Http\Middleware;

use App\Http\Datas\UserPlanFeature;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class UserFeatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (($user = auth()->user()) instanceof User) {
            $planInfo = UserPlanFeature::fromPlan($user->currentPlan);

            app()->instance('UserPlanFeature', $planInfo);
            View::share('userPlanFeature', $planInfo);
        }

        return $next($request);
    }
}
