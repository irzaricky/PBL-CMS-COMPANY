<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiCacheService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CacheController extends Controller
{
    protected ApiCacheService $cacheService;

    public function __construct(ApiCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Mengambil statistik cache
     * 
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = $this->cacheService->getCacheStats();

            return response()->json([
                'status' => 'success',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get cache statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus cache untuk endpoint tertentu
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function clearEndpoint(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint' => 'required|string'
        ]);

        try {
            $this->cacheService->clearEndpointCache($request->endpoint);

            return response()->json([
                'status' => 'success',
                'message' => "Cache cleared for endpoint: {$request->endpoint}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to clear cache for endpoint',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus semua cache API
     * 
     * @return JsonResponse
     */
    public function clearAll(): JsonResponse
    {
        try {
            $this->cacheService->clearAllApiCache();

            return response()->json([
                'status' => 'success',
                'message' => 'All API cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to clear all cache',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Memanaskan cache untuk endpoint populer
     * 
     * @return JsonResponse
     */
    public function warmup(): JsonResponse
    {
        try {
            $popularEndpoints = [
                '/api/artikel',
                '/api/artikel/categories',
                '/api/event',
                '/api/produk',
                '/api/profil-perusahaan',
                '/api/media-sosial',
                '/api/konten-slider',
                '/api/feature-toggles'
            ];

            $warmedEndpoints = [];

            foreach ($popularEndpoints as $endpoint) {
                try {
                    // Make internal request to warm up cache
                    $response = app('Illuminate\Contracts\Http\Kernel')
                        ->handle(\Illuminate\Http\Request::create($endpoint, 'GET'));

                    if ($response->getStatusCode() === 200) {
                        $warmedEndpoints[] = $endpoint;
                    }
                } catch (\Exception $e) {
                    // Skip failed endpoints
                    continue;
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Cache warmup completed',
                'warmed_endpoints' => $warmedEndpoints
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to warm up cache',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
