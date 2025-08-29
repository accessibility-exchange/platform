<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(fn () => view('filament.admin.logo'))
            ->brandLogoHeight('2.5rem')
            ->colors([
                'primary' => '#545dbb',
                'danger' => '#e11d48',
                'success' => '#15803d',
                'warning' => '#ca8a04',
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
            ->navigationItems([
                NavigationItem::make(__('Dashboard'))
                    ->url(fn () => localized_route('dashboard'))
                    ->icon('heroicon-m-view-columns')
                    ->sort(-3),
                NavigationItem::make(__('Manage accounts'))
                    ->url(fn () => localized_route('admin.manage-accounts'))
                    ->sort(-2)
                    ->group(__('Manage')),
                NavigationItem::make(__('Estimates and agreements'))
                    ->url(fn () => localized_route('admin.estimates-and-agreements'))
                    ->sort(-1)
                    ->group(__('Manage')),
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Analytics')
                    ->icon('heroicon-s-bolt'),
                NavigationGroup::make()
                    ->label('Manage')
                    ->icon('heroicon-s-wrench'),
                NavigationGroup::make()
                    ->label('Pages, resources and training')
                    ->icon('heroicon-s-building-library'),
                NavigationGroup::make()
                    ->label('Metadata')
                    ->icon('heroicon-s-square-3-stack-3d'),
                NavigationGroup::make()
                    ->label('Settings')
                    ->icon('heroicon-s-cog'),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
            ]);
    }
}
