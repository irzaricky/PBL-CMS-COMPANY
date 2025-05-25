<?php
namespace App\Http\Controllers\Api;

use App\Models\FeatureToggle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class FeatureToggleController extends Controller
{
    /**
     * Mengambil daftar fitur yang diaktifkan
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cacheKey = 'feature_toggles.index';
        $timestampKey = $cacheKey . '.timestamp';
        $cacheDuration = 1800; // 30 minutes - feature toggles rarely change

        $features = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
            // Store timestamp when cache is created
            Cache::put($timestampKey, now()->toISOString(), 1800);
            return FeatureToggle::select('key', 'status_aktif')
                ->get()
                ->pluck('status_aktif', 'key');
        });

        return response()->json([
            'data' => $features,
            'cached_at' => Cache::get($timestampKey, now()->toISOString()),
            'cache_key' => $cacheKey
        ]);
    }
}
