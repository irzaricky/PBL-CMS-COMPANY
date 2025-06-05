<?php

namespace App\Observers;

use App\Services\ApiCacheService;
use App\Models\TestimoniProduk;

class TestimoniProdukObserver
{
    protected ApiCacheService $cacheService;

    public function __construct(ApiCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the TestimoniProduk "created" event.
     */
    public function created(TestimoniProduk $testimoniProduk): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the TestimoniProduk "updated" event.
     */
    public function updated(TestimoniProduk $testimoniProduk): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the TestimoniProduk "deleted" event.
     */
    public function deleted(TestimoniProduk $testimoniProduk): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Clear all testimoni produk-related cache
     */
    protected function clearRelatedCache(): void
    {
        $this->cacheService->clearEndpointCache('api/testimoni');
    }
}
