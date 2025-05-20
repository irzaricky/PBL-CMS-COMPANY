<?php

namespace App\Providers;

use App\Models\User;
use Inertia\Inertia;
use Filament\Facades\Filament;
use App\Models\ProfilPerusahaan;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse as RegistrationResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoginResponseContract::class, \App\Http\Responses\LoginResponse::class);
        $this->app->bind(LogoutResponseContract::class, \App\Http\Responses\LogoutResponse::class);
        $this->app->bind(RegistrationResponseContract::class, \App\Http\Responses\RegistrationResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Inertia::share([
            'auth' => function () {
                return [
                    'user' => Auth::user(),
                ];
            },
        ]);

        Inertia::share([
            'theme' => function () {
                try {
                    $profil = ProfilPerusahaan::first();
                    return [
                        'secondary' => $profil?->tema_perusahaan ?? '#31487A',
                    ];
                } catch (\Exception $e) {
                    return [
                        'secondary' => '#31487A',
                    ];
                }
            },
        ]);

        try {
            $profil = ProfilPerusahaan::first();
            $logo = $profil?->logo_perusahaan ?? 'favicon.ico';
            $titlePerusahaan = $profil?->nama_perusahaan ?? 'Sistem Informasi Manajemen';

            // Share values to views
            View::share('logoPerusahaan', $logo);
            View::share('titlePerusahaan', $titlePerusahaan);

            // Set the application name (for title)
            config(['app.name' => $titlePerusahaan]);
        } catch (\Exception $e) {
            // Set default values if database is not available
            View::share('logoPerusahaan', 'favicon.ico');
            View::share('titlePerusahaan', 'Sistem Informasi Manajemen');
            config(['app.name' => 'Sistem Informasi Manajemen']);
        }
    }
}
