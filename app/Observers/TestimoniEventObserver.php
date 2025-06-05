<?php

namespace App\Observers;

use App\Services\ApiCacheService;
use App\Models\TestimoniEvent;

class TestimoniEventObserver
{
    protected ApiCacheService $cacheService;

    public function __construct(ApiCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the TestimoniEvent "created" event.
     */
    public function created(TestimoniEvent $testimoniEvent): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the TestimoniEvent "updated" event.
     */
    public function updated(TestimoniEvent $testimoniEvent): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the TestimoniEvent "deleted" event.
     */
    public function deleted(TestimoniEvent $testimoniEvent): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Clear all testimoni event-related cache
     */
    protected function clearRelatedCache(): void
    {
        $this->cacheService->clearEndpointCache('api/testimoni');
    }
}
