<?php

namespace App\Providers\Filament;

use App\Filament\App\Pages\Home;
use App\Filament\App\Pages\ReadPage;
use App\Filament\App\Pages\Register;
use App\Filament\App\Pages\UserDomains;
use App\Filament\App\Pages\UserMails;
use App\Filament\App\Pages\UserPlanHistory;
use App\Filament\Pages\AdminDashboard;
use App\Http\Middleware\AutoLoginViaTelegram;
use App\Http\Middleware\SetLocale;
use App\Livewire\CustomProfileComponent;
use Artesaos\SEOTools\Facades\SEOTools;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class AppPanelProvider extends PanelProvider
{
    /**
     * @throws \Exception
     */
    public function panel(Panel $panel): Panel
    {
        $primaryColor = $siteSetting->main_color ?? Color::Sky;
        return $panel
            ->id('app')
//            ->spa()
            ->path('')
            ->colors([
                'primary' => $primaryColor,
            ])
            ->font('Space Grotesk', provider: GoogleFontProvider::class)
            ->viteTheme('resources/css/filament/app/theme.css')
            ->brandLogo(asset('logo-light.svg'))
            ->darkModeBrandLogo(asset('logo.svg'))
            ->brandLogoHeight('4rem')
            ->favicon(asset('favicon.png'))
//            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Home::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                //                Widgets\AccountWidget::class,
                //                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                AutoLoginViaTelegram::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->authMiddleware([
                //                                Authenticate::class,
            ])
            ->topNavigation()
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn(): ?string => setting('site.meta_html'),
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn(): ?string => user_has_feature('no_ads') ? '' : setting('ads.global_ads'),
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn(): ?string => SEOTools::generate(),
            )
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_AFTER,
                fn (): ?string => Blade::render('<x-theme-mode-fi/>'),
            )
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_AFTER,
                fn (): string => Blade::render('<x-switchable-language-fi/>'),
            )
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_AFTER,
                fn(): string => Blade::render('<x-user-login-fi/>'),
            )
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn (): string => Blade::render('<x-footer/>'),
            )
            ->renderHook(
                PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
                fn(): string => Blade::render('<x-header-notifications/>'),
            )
            ->renderHook(
                'panels::body.end', // Hook name (render before closing </body>)
                fn(): string => Blade::render("@vite('resources/js/app.js')"), // Render the vite directive
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn(): string => Blade::render('<x-authenticate-passkey />'),
            )
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->items([
                    ...Home::getNavigationItems(),
                    NavigationItem::make(__('API'))
                        ->url(fn() => route(ReadPage::getRouteName('app'), ['slug' => 'api']))
                        ->icon('heroicon-o-code-bracket')
                        ->isActiveWhen(fn() => request()->fullUrlIs(ReadPage::getUrl(['slug' => 'api'])))
                        ->sort(10),
                    NavigationItem::make('upgrade')
                        ->label(__('Premium'))
                        ->visible(!auth()->check() || !auth()->user()->hasPremium())
                        ->url('javascript:window.dispatchEvent(new CustomEvent(\'open-modal\', { detail: { id: \'upgrade-modal\' } }));') // Không redirect
                        ->icon(fn(): HtmlString => new HtmlString('<svg class="w-5 h-5 text-yellow-400" viewBox="0 0 24 24"><g fill="none"><path fill="currentColor" d="m19.87 12.388l-.745-.08zm-.183 1.705l.746.08zm-15.374 0l-.746.08zm-.184-1.705l.746-.08zm4.631-1.454l.655.365zm1.79-3.209l-.655-.365zm2.9 0l-.655.366zm1.79 3.209l.655-.365zm.764 1.025l-.303.687zm1.467-.714l-.53-.531zm-1.018.777l-.102-.743zm-9.923-.777l-.53.532zm1.017.777l.102-.743zm.45-.063l.301.687zm-2.285 8.194l.5-.559zm12.576 0l-.5-.559zm.576-10.173l.568-.49zm-5.956-3.197l-.341-.668zm-1.816 0l.341-.668zm8.033 5.525l-.183 1.705l1.49.16l.184-1.704zm-6.037 7.942h-2.176v1.5h2.176zm-8.03-6.237l-.183-1.705l-1.491.16l.183 1.705zm4.357-2.714l1.79-3.208l-1.31-.73l-1.79 3.208zm3.38-3.208l1.79 3.208l1.31-.73l-1.79-3.209zm1.79 3.208c.162.29.31.56.455.765c.149.211.351.445.662.582l.604-1.373c.056.024.046.05-.039-.071a8 8 0 0 1-.372-.633zm2.356-.585c-.258.258-.412.41-.533.507c-.115.093-.117.066-.057.058l.205 1.486c.336-.047.595-.216.796-.378c.195-.158.412-.376.648-.61zm-1.24 1.932c.269.118.565.159.855.119l-.205-1.486a.1.1 0 0 1-.045-.006zm-9.7-.87c.235.235.452.453.647.61c.201.164.46.332.796.379l.205-1.486c.06.008.058.035-.057-.058a8 8 0 0 1-.533-.507zm2.104-1.207a8 8 0 0 1-.373.633c-.084.12-.094.095-.038.07l.604 1.374c.31-.137.514-.37.662-.582c.144-.206.293-.475.455-.765zm-.661 2.196c.29.04.586-.001.854-.12l-.604-1.372a.1.1 0 0 1-.045.006zm3.468 7.485c-1.438 0-2.445-.001-3.213-.1c-.748-.095-1.17-.273-1.487-.556l-1 1.118c.63.564 1.39.81 2.296.926c.886.113 2.006.112 3.404.112zm-7.345-6.077c.148 1.378.266 2.727.466 3.821c.101.552.229 1.072.405 1.523c.175.448.417.875.774 1.195l1-1.118c-.116-.104-.248-.294-.377-.623q-.19-.488-.326-1.247c-.188-1.022-.297-2.28-.45-3.711zm15.375-.16c-.154 1.431-.264 2.689-.45 3.71q-.138.76-.327 1.248c-.129.329-.261.52-.377.623l1 1.118c.357-.32.599-.747.774-1.195c.176-.451.304-.971.405-1.523c.2-1.094.318-2.443.466-3.82zm-5.854 7.737c1.398 0 2.518.001 3.404-.112c.907-.116 1.666-.362 2.296-.926l-1-1.118c-.317.283-.739.46-1.487.556c-.768.099-1.775.1-3.213.1zM10.75 5c0-.69.56-1.25 1.25-1.25v-1.5A2.75 2.75 0 0 0 9.25 5zM12 3.75c.69 0 1.25.56 1.25 1.25h1.5A2.75 2.75 0 0 0 12 2.25zM20.75 9a.75.75 0 0 1-.75.75v1.5A2.25 2.25 0 0 0 22.25 9zm-1.5 0a.75.75 0 0 1 .75-.75v-1.5A2.25 2.25 0 0 0 17.75 9zm.75-.75a.75.75 0 0 1 .75.75h1.5A2.25 2.25 0 0 0 20 6.75zM4 9.75A.75.75 0 0 1 3.25 9h-1.5A2.25 2.25 0 0 0 4 11.25zM3.25 9A.75.75 0 0 1 4 8.25v-1.5A2.25 2.25 0 0 0 1.75 9zM4 8.25a.75.75 0 0 1 .75.75h1.5A2.25 2.25 0 0 0 4 6.75zm16 1.5h-.009l-.017 1.5H20zm.616 2.719c.049-.45.091-.843.114-1.171a4.6 4.6 0 0 0-.004-.898l-1.487.2c.015.11.016.29-.005.592c-.02.294-.06.657-.11 1.116zm-.625-2.719a.75.75 0 0 1-.559-.26l-1.135.98c.406.47 1.006.772 1.677.78zm-.559-.26A.74.74 0 0 1 19.25 9h-1.5c0 .561.207 1.076.547 1.47zM18 11.777c.677-.675 1.026-1.015 1.258-1.159l-.787-1.276c-.42.26-.924.768-1.53 1.372zM4.75 9a.74.74 0 0 1-.182.49l1.135.98c.34-.394.547-.909.547-1.47zm2.309 1.714c-.606-.604-1.11-1.113-1.53-1.372l-.787 1.276c.232.144.58.484 1.258 1.159zM4.568 9.49a.75.75 0 0 1-.559.26l.017 1.5a2.25 2.25 0 0 0 1.677-.78zm-.559.26H4v1.5h.026zm.866 2.558a33 33 0 0 1-.109-1.116a3 3 0 0 1-.005-.592l-1.487-.2a4.6 4.6 0 0 0-.004.898c.023.328.065.72.114 1.17zM13.25 5c0 .485-.276.907-.683 1.115l.681 1.336A2.75 2.75 0 0 0 14.75 5zm-.683 1.115c-.17.086-.361.135-.567.135v1.5a2.74 2.74 0 0 0 1.249-.3zm1.538 1.245c-.206-.37-.391-.703-.561-.975l-1.272.795c.146.234.31.53.523.91zM12 6.25c-.206 0-.398-.05-.567-.135l-.681 1.336c.375.191.8.299 1.248.299zm-.567-.135A1.25 1.25 0 0 1 10.75 5h-1.5a2.75 2.75 0 0 0 1.502 2.45zm-.228 1.976c.212-.382.377-.677.523-.91l-1.272-.796c-.17.272-.355.605-.561.975z"></path><path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M5 17.5h14" opacity=".5"></path></g></svg>')),
                ])
                    ->groups([
                        NavigationGroup::make(__('Personal'))
                            ->items(auth()->check() ?
                                [
                                    ...UserMails::getNavigationItems(),
                                    ...UserDomains::getNavigationItems(),
                                ]
                                : []),
                        NavigationGroup::make(__('Help'))
                            ->items([
                                NavigationItem::make(fn() => __('Recover deleted emails'))
                                    ->url(fn() => route(ReadPage::getRouteName('app'), ['slug' => 'recovery-deleted-mail']))
                                    ->icon('heroicon-o-arrow-path-rounded-square')
                                    ->isActiveWhen(fn() => request()->fullUrlIs(ReadPage::getUrl(['slug' => 'recovery-deleted-mail'])))
                                    ->sort(10),
                            ]),
                    ]);
            })
            ->userMenuItems([
                MenuItem::make()
                    ->url(fn(): string => AdminDashboard::getUrl(panel: 'admin'))
                    ->label(__('Admin dashboard'))
                    ->icon('heroicon-o-shield-check')
                    ->visible(fn(): bool => auth()->check() && auth()->user()->isAdmin()),
                MenuItem::make()
                    ->url(fn(): string => UserPlanHistory::getUrl())
                    ->label(__('My Plan History'))
                    ->icon('heroicon-o-arrow-up-circle'),
            ])
            ->authGuard('web')
            ->plugins([
                BreezyCore::make()
                    ->myProfile(slug: 'profile')
                    ->myProfileComponents([
                        CustomProfileComponent::class,
                    ])
                    ->enableTwoFactorAuthentication()
                    ->enableSanctumTokens(permissions: ['mail:create', 'mail:read', 'mail:list', 'mail:delete', 'netflix:read']),
            ])
            ->login()
            ->registration(Register::class)
            ->passwordReset();
    }
}
