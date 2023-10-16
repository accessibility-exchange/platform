<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
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
            ->colors([
                'primary' => [
                    50 => '88, 91, 156',
                    100 => '78, 81, 146',
                    200 => '68, 71, 136',
                    300 => '58, 61, 126',
                    400 => '48, 51, 116',
                    500 => '38, 41, 106',
                    600 => '28, 31, 96',
                    700 => '18, 21, 86',
                    800 => '8, 11, 76',
                    900 => '0, 1, 66',
                ],
                'danger' => [
                    50 => '255, 241, 242',
                    100 => '255, 228, 230',
                    200 => '254, 205, 211',
                    300 => '253, 164, 175',
                    400 => '251, 113, 133',
                    500 => '244, 63, 94',
                    600 => '225, 29, 72',
                    700 => '190, 18, 60',
                    800 => '159, 18, 57',
                    900 => '136, 19, 55',
                ],
                'success' => [
                    50 => '240, 253, 244',
                    100 => '220, 252, 231',
                    200 => '187, 247, 208',
                    300 => '134, 239, 172',
                    400 => '74, 222, 128',
                    500 => '34, 197, 94',
                    600 => '22, 163, 74',
                    700 => '21, 128, 61',
                    800 => '22, 101, 52',
                    900 => '20, 83, 45',
                ],
                'warning' => [
                    50 => '254, 252, 232',
                    100 => '254, 249, 195',
                    200 => '254, 240, 138',
                    300 => '253, 224, 71',
                    400 => '250, 204, 21',
                    500 => '234, 179, 8',
                    600 => '202, 138, 4',
                    700 => '161, 98, 7',
                    800 => '133, 77, 14',
                    900 => '113, 63, 18',
                ],
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
