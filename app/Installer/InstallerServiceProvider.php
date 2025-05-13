<?php

namespace App\Installer;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Installer\Components\InstallError;
use App\Installer\Components\InstallInput;
use App\Installer\Components\InstallSelect;
use App\Installer\Middleware\InstallMiddleware;

class InstallerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('App\Installer\Controllers\InstallerController', function ($app) {
            return new \App\Installer\Controllers\InstallerController(
                $app->make('App\Installer\Main\PermissionsChecker'),
                $app->make('App\Installer\Main\RequirementsChecker')
            );
        });

        $this->app->bind('App\Installer\Controllers\DatabaseController', function ($app) {
            return new \App\Installer\Controllers\DatabaseController(
                $app->make('App\Installer\Main\EnvironmentManager')
            );
        });

        $this->loadViewsFrom(__DIR__ . '/Views', 'InstallerEragViews');
    }

    /**
     * Bootstrap services.
     */
    public function boot(Router $router): void
    {
        view()->share('errors', app('Illuminate\Support\ViewErrorBag'));

        $router->middlewareGroup('installCheck', [InstallMiddleware::class]);

        Blade::component('install-input', InstallInput::class);
        Blade::component('install-error', InstallError::class);
        Blade::component('install-select', InstallSelect::class);
    }
}
