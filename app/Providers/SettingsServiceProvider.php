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
        $metaSetting = app(MetasSettings::class);
        $paymentSetting = app(PaymentsSettings::class);
        $siteSetting = app(SiteSettings::class);
        View::share('metaSetting', $metaSetting);
        View::share('paymentSetting', $paymentSetting);
        View::share('siteSetting', $siteSetting);
    }
}
