<?php

namespace App\Observers;

use App\Services\ApiCacheService;
use App\Models\Feedback;

class FeedbackObserver
{
    protected ApiCacheService $cacheService;

    public function __construct(ApiCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the Feedback "created" event.
     */
    public function created(Feedback $feedback): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the Feedback "updated" event.
     */
    public function updated(Feedback $feedback): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Handle the Feedback "deleted" event.
     */
    public function deleted(Feedback $feedback): void
    {
        $this->clearRelatedCache();
    }

    /**
     * Clear all feedback-related cache
     */
    protected function clearRelatedCache(): void
    {
        $this->cacheService->clearEndpointCache('api/feedback');
    }
}
