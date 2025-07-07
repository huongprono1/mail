<?php

namespace App\Providers\Filament;

use App\Filament\Pages\AdminDashboard;
use Croustibat\FilamentJobsMonitor\FilamentJobsMonitorPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Rmsramos\Activitylog\ActivitylogPlugin;
use SolutionForest\FilamentTranslateField\FilamentTranslateFieldPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandLogo(asset('logo-light.svg'))
            ->darkModeBrandLogo(asset('logo.svg'))
            ->brandLogoHeight('4rem')
            ->favicon(asset('favicon.png'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                AdminDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('admin')
            ->plugins([
                SpatieLaravelTranslatablePlugin::make()->defaultLocales(['vi', 'en']),
                FilamentTranslateFieldPlugin::make(),
                \BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin::make(),
                BreezyCore::make()
                    ->myProfile(slug: 'profile')
                    ->enableTwoFactorAuthentication()
                    ->enableSanctumTokens(),
                FilamentJobsMonitorPlugin::make()
                    ->navigationGroup('Stats')
                    ->enableNavigation(),
                ActivitylogPlugin::make()->navigationGroup('Stats'),
                \Awcodes\Curator\CuratorPlugin::make()
                    ->label('Media')
                    ->pluralLabel('Media')
                    ->navigationIcon('heroicon-o-photo')
                    ->navigationGroup('Blog')
                    ->navigationSort(3)
                    ->navigationCountBadge()
                    ->defaultListView('grid'),
            ])
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->spa()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Blog')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Settings')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Pricing')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Stats')
                    ->collapsed(),
            ])
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchDebounce('750ms')
//            ->unsavedChangesAlerts()
            ->databaseNotifications();
        //            ->renderHook(
        //                'panels::body.end', // Hook name (render before closing </body>)
        //                fn(): string => Blade::render("@vite('resources/js/filament/admin.js')"), // Render the vite directive
        //            )
        //            ->renderHook(
        //                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
        //                fn(): string => Blade::render('<x-authenticate-passkey />'),
        //            );
    }
}
