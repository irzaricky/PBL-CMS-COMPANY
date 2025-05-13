<?php

namespace App\Providers;

use App\Models\User;
use Inertia\Inertia;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Blade;
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
        Blade::component('install-input', \App\Installer\Components\InstallInput::class);
        Blade::component('install-error', \App\Installer\Components\InstallError::class);
        Blade::component('install-select', \App\Installer\Components\InstallSelect::class);

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
    }
}
