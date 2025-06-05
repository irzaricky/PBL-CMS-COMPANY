<?php

namespace App\Observers;

use App\Services\ApiCacheService;
use App\Models\Testimoni;

class TestimoniObserver
{
    protected ApiCacheService $cacheService;

    public function __construct(ApiCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the Testimoni "created" event.
     */
    public function created(Testimoni $testimoni): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the Testimoni "updated" event.
     */
    public function updated(Testimoni $testimoni): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the Testimoni "deleted" event.
     */
    public function deleted(Testimoni $testimoni): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Clear all testimoni-related cache
     */
    protected function clearRelatedCache(): void
    {
        $this->cacheService->clearEndpointCache('api/testimoni');
    }
}
