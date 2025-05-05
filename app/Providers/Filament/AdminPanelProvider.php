<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use App\Models\ProfilPerusahaan;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\UserResource;
use \App\Http\Middleware\CheckStatusUser;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\UnduhanResource;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->spa()
            ->topNavigation()
            ->id('admin')
            ->path('admin')
            ->brandName(ProfilPerusahaan::first()->nama_perusahaan ?? 'Admin Panel')
            ->login()
            ->unsavedChangesAlerts()
            ->font('Plus jakarta Sans')
            ->registration()
            ->colors([
                'primary' => '#3b82f6',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,

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
                CheckStatusUser::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])
            // ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
            //     return $builder->items([
            //         NavigationItem::make('Dashboard')
            //             ->icon('heroicon-o-home')
            //             ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
            //             ->url(fn (): string => Dashboard::getUrl()),
            //         ...UserResource::getNavigationItems(),
            //         ...UnduhanResource::getNavigationItems(),
            //     ]);
            // });
            ->userMenuItems([
                'heroicon-o-home' => MenuItem::make()
                    ->icon('heroicon-o-home')
                    ->label('Home')
                    ->url('/'), // Mengarah ke halaman utama
            ]);;
    }
}
