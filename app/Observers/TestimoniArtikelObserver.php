<?php

namespace App\Observers;

use App\Services\ApiCacheService;
use App\Models\TestimoniArtikel;

class TestimoniArtikelObserver
{
    protected ApiCacheService $cacheService;

    public function __construct(ApiCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the TestimoniArtikel "created" event.
     */
    public function created(TestimoniArtikel $testimoniArtikel): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the TestimoniArtikel "updated" event.
     */
    public function updated(TestimoniArtikel $testimoniArtikel): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the TestimoniArtikel "deleted" event.
     */
    public function deleted(TestimoniArtikel $testimoniArtikel): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Clear all testimoni artikel-related cache
     */
    protected function clearRelatedCache(): void
    {
        $this->cacheService->clearEndpointCache('api/testimoni');
    }
}
