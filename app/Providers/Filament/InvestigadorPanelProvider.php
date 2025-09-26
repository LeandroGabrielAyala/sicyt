<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use App\Http\Middleware\RedirectToCorrectPanel;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class InvestigadorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('investigadorpanel')
            ->path('investigadorpanel')
            ->login()
            ->brandLogo(asset('images/logo-sicyt.png'))
            ->favicon(asset('images/logo-sicyt.png'))
            ->colors([
                'primary' => Color::Amber,
            ])
            // Descubrir recursos de App\Filament\App\Resources
            ->discoverResources(
                in: app_path('Filament/Investigador/Resources'),
                for: 'App\\Filament\\Investigador\\Resources'
            )
            // Descubrir páginas
            ->discoverPages(
                in: app_path('Filament/Investigador/Pages'),
                for: 'App\\Filament\\Investigador\\Pages'
            )
            ->pages([
                Pages\Dashboard::class,
            ])
            // Widgets
            ->discoverWidgets(
                in: app_path('Filament/Investigador/Widgets'),
                for: 'App\\Filament\\Investigador\\Widgets'
            )
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            // Middleware de sesión y redirección
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
                RedirectToCorrectPanel::class, // tu middleware para redirigir según rol
            ]);
    }
}
