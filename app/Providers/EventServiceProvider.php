<?php

namespace App\Providers;

use App\Models\Lamaran;
use App\Observers\LamaranObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings (sync) for the application.
     *
     * @var array<string, array<int, class-string>>
     */
    // No event listeners registered here now
    protected $listen = [];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Register the observer
        Lamaran::observe(LamaranObserver::class);
    }
}
