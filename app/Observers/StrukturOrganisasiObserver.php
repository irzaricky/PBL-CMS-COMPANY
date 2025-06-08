<?php

namespace App\Observers;

use App\Services\ApiCacheService;
use App\Models\StrukturOrganisasi;

class StrukturOrganisasiObserver
{
    protected ApiCacheService $cacheService;

    public function __construct(ApiCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the StrukturOrganisasi "created" event.
     */
    public function created(StrukturOrganisasi $strukturOrganisasi): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the StrukturOrganisasi "updated" event.
     */
    public function updated(StrukturOrganisasi $strukturOrganisasi): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the StrukturOrganisasi "deleted" event.
     */
    public function deleted(StrukturOrganisasi $strukturOrganisasi): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Clear all struktur organisasi-related cache
     */
    protected function clearRelatedCache(): void
    {
        $this->cacheService->clearEndpointCache('api/struktur-organisasi');
    }
}
