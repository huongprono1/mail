<?php

namespace App\Providers;

use App\Settings\MetasSettings;
use App\Settings\PaymentsSettings;
use App\Settings\SiteSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $siteSetting = app(SiteSettings::class);
        View::share('siteSetting', $siteSetting);
    }
}
