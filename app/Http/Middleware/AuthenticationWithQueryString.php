<?php

namespace App\Http\Middleware;

use App\Http\Datas\ApiResponse;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationWithQueryString
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->query('apiKey');
        if(!$apiKey){
            return ApiResponse::success(
                message: 'API key is required',
                status: Response::HTTP_UNAUTHORIZED
            );
        }

        $accessToken = PersonalAccessToken::findToken($apiKey);

        if (!$accessToken) {
            return ApiResponse::success(
                message: 'Unauthenticated',
                status: Response::HTTP_UNAUTHORIZED
            );
        }

        $user = $accessToken->tokenable;

        if (!$user) {
            return ApiResponse::success(
                message: 'Unauthenticated',
                status: Response::HTTP_UNAUTHORIZED
            );
        }

        // Set current access token cho user (để có thể sử dụng các method của Sanctum)
        $user->withAccessToken($accessToken);

        // Authenticate user
//        Auth::guard($guards[0] ?? config('sanctum.guard', 'web'))->setUser($user);

        // Set user resolver cho request
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        // Cập nhật last_used_at (tương tự như Sanctum)
        $accessToken->forceFill(['last_used_at' => now()])->save();

        return $next($request);
    }
}
