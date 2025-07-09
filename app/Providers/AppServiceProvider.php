<?php

namespace App\Providers;

use App\Http\Datas\ApiResponse;
use App\Models\User;
use App\Services\UserFeatureService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Mcamara\LaravelLocalization\Traits\LoadsTranslatedCachedRoutes;

class AppServiceProvider extends ServiceProvider
{
    use LoadsTranslatedCachedRoutes;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Rate limit api
        RateLimiter::for('api', function (Request $request) {
            $limit = $request->user() ? (new UserFeatureService(auth()->user()))->getFeatureValue('api-throttle', 10) : 10;
            return Limit::perMinute($limit)
                ->by(optional($request->user())->id ?: $request->ip())
                ->response(
                    fn() => ApiResponse::error('Too many requests. Limit of ' . $limit . ' per minutes', 429)
                );
        });

        Blade::if('feature', function (string $featureSlug) {
            return user_has_feature($featureSlug); // Sử dụng helper đã tạo
        });

        // Directive để kiểm tra với user cụ thể (nếu cần)
        Blade::if('userfeature', function (string $featureSlug, User $user) {
            return user_has_feature($featureSlug, $user);
        });

        Blade::directive('content_block', function ($key) {
            return content_block($key);
        });

        RouteServiceProvider::loadCachedRoutesUsing(fn() => $this->loadCachedRoutes());

        // Load telegram route
        $this->loadRoutesFrom(__DIR__ . '/../../routes/telegram.php');
    }

    protected function handleUserResolved($user)
    {
        // Logic của bạn ở đây
        // Ví dụ: log user activity, set user preferences, etc.

        // Log user access
        \Log::info('User authenticated', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'timestamp' => now()
        ]);

        // Set user timezone nếu có
        if ($user->timezone) {
            config(['app.timezone' => $user->timezone]);
        }
    }
}
