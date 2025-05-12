<?php

namespace App\Providers;

use Inertia\Inertia;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;
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
    }
}
