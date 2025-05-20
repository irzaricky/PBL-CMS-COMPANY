<?php

namespace App\Providers\Filament;


use Filament\Pages;
use Filament\Panel;
use App\Models\User;
use Filament\PanelProvider;
use App\Filament\Widgets\Admin\TotalUsersWidget;
use App\Filament\Widgets\Admin\UsersByRoleWidget;
use App\Filament\Widgets\Admin\StorageUsageChart;
use App\Filament\Widgets\Admin\StorageUsageByFeatureChart;
use App\Filament\Widgets\Admin\RemainingStorageWidget;
use App\Filament\Widgets\ContentManager\Artikel\TrendArticlesChart;
use App\Filament\Widgets\ContentManager\Artikel\TopArticlesByViews;
use App\Filament\Widgets\ContentManager\CaseStudy\CaseStudyStatusChart;
use App\Filament\Widgets\ContentManager\Event\UpcomingEventsChart;
use App\Filament\Widgets\ContentManager\Galeri\GaleriDownloadsChart;
use App\Filament\Widgets\ContentManager\Galeri\GaleriStatusChart;
use App\Filament\Widgets\ContentManager\General\ContentCountsChart;
use App\Filament\Widgets\ContentManager\General\ContentTrendsChart;
use App\Filament\Widgets\ContentManager\Produk\ProductsByStatusChart;
use App\Filament\Widgets\ContentManager\Produk\TopProducts;
use App\Filament\Widgets\ContentManager\Unduhan\DocumentDownloadsChart;
use App\Filament\Widgets\ContentManager\Unduhan\TopDownloads;
use App\Filament\Widgets\ContentManager\Unduhan\UnduhanStatusChart;
use App\Models\ProfilPerusahaan;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use App\Filament\Pages\Auth\Register;
use Filament\Navigation\NavigationItem;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Widgets\ContentManager\Event\EventStatusChart;
use App\Filament\Widgets\ContentManager\Event\EventTrendsChart;
use App\Filament\Widgets\ContentManager\Event\TopEventsStatsWidget;
use App\Filament\Widgets\ContentManager\Galeri\GaleriTrendsChart;
use App\Filament\Widgets\ContentManager\Galeri\TopGaleriesStatsWidget;
use \App\Http\Middleware\CheckStatusUser;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\UnduhanResource;
use Intervention\Image\ImageServiceProvider;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
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
            ->widgets([
                    // Admin widgets
                TotalUsersWidget::class,
                UsersByRoleWidget::class,
                StorageUsageChart::class,
                RemainingStorageWidget::class,
                StorageUsageByFeatureChart::class,

                    // Content Manager widgets
                ContentCountsChart::class,
                ContentTrendsChart::class,

                TopArticlesByViews::class,
                TrendArticlesChart::class,

                CaseStudyStatusChart::class,

                TopEventsStatsWidget::class,
                EventTrendsChart::class,
                UpcomingEventsChart::class,
                EventStatusChart::class,


                TopGaleriesStatsWidget::class,
                GaleriTrendsChart::class,
                GaleriDownloadsChart::class,
                GaleriStatusChart::class,

                TopProducts::class,
                ProductsByStatusChart::class,


                TopDownloads::class,
                DocumentDownloadsChart::class,
                UnduhanStatusChart::class,

                // Customer manager widgets

                // Director widgets
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
                AuthUIEnhancerPlugin::make()
                    ->formPanelPosition('right')
                    ->mobileFormPanelPosition('bottom')
                    ->emptyPanelBackgroundImageUrl('https://images.pexels.com/photos/466685/pexels-photo-466685.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2')
                    ->formPanelWidth('40%'),
                FilamentApexChartsPlugin::make()
            ])
            ->userMenuItems([
                'heroicon-o-home' => MenuItem::make()
                    ->icon('heroicon-s-power')
                    ->label('Keluar dashboard')
                    ->url('/'),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css');
    }
}
