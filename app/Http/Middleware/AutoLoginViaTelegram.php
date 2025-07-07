<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AutoLoginViaTelegram
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $originalPath = Str::after($request->path(), $request->route('locale') . '/');
        $temporaryRequest = Request::create(url($originalPath), $request->method(), $request->all());

        if (URL::hasValidSignature($temporaryRequest) && $request->get('telegram_id')) {
            $user = User::where('telegram_id', $request->get('telegram_id'))->first();
            if ($user) {
                auth()->login($user);
            }
        }

        return $next($request);
    }
}
