<?php

namespace App\Observers;

use App\Services\ApiCacheService;
use App\Models\ProfilPerusahaan;

class ProfilPerusahaanObserver
{
    protected ApiCacheService $cacheService;

    public function __construct(ApiCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the ProfilPerusahaan "created" event.
     */
    public function created(ProfilPerusahaan $profilPerusahaan): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the ProfilPerusahaan "updated" event.
     */
    public function updated(ProfilPerusahaan $profilPerusahaan): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the ProfilPerusahaan "deleted" event.
     */
    public function deleted(ProfilPerusahaan $profilPerusahaan): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Clear all profil perusahaan-related cache
     */
    protected function clearRelatedCache(): void
    {
        $this->cacheService->clearEndpointCache('api/profil-perusahaan');
    }
}
