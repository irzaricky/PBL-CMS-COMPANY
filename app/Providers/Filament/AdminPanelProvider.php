<?php

namespace App\Providers\Filament;

use Directory;
use Filament\Pages;
use Filament\Panel;
use App\Models\User;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use App\Models\ProfilPerusahaan;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use App\Filament\Pages\Auth\Register;
use Filament\Navigation\NavigationItem;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use \App\Http\Middleware\CheckStatusUser;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\UnduhanResource;
use Intervention\Image\ImageServiceProvider;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->spa()
            ->topNavigation()
            ->brandName(function () {
                try {
                    if (\Illuminate\Support\Facades\Schema::hasTable('profil_perusahaan')) {
                        $company = ProfilPerusahaan::first();
                        return $company ? $company->nama_perusahaan : 'Admin Panel';
                    }
                } catch (\Exception $e) {
                }
                return 'Admin Panel';
            })
            ->globalSearch(false)
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile(EditProfile::class)
            ->unsavedChangesAlerts()
            ->globalSearch(false)
            ->font('Plus jakarta Sans')
            ->registration(Register::class)
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
                FilamentApexChartsPlugin::make()
            ])
            ->userMenuItems([
                'heroicon-o-home' => MenuItem::make()
                    ->icon('heroicon-s-power')
                    ->label('Keluar dashboard')
                    ->url('/'),
            ])
            ->plugins([
                FilamentBackgroundsPlugin::make()
                    ->remember(900)
                    ->imageProvider(MyImages::make()
                        ->directory(
                            'images\swisnl\filament-backgrounds\triangles'
                        )),
            ]);
    }
}
