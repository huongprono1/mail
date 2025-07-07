<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\AppPanelProvider::class,
    App\Providers\TelescopeServiceProvider::class,
    Artesaos\SEOTools\Providers\SEOToolsServiceProvider::class,
    Torann\GeoIP\GeoIPServiceProvider::class,
    \App\Providers\SettingsServiceProvider::class
];
