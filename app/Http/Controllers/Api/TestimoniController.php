<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use App\Http\Resources\TestimoniResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestimoniController extends Controller
{
    /**
     * Mengambil daftar testimoni yang terpublikasi
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $cacheKey = 'testimoni_published_list';

            $result = Cache::flexible($cacheKey, [900, 1800], function () { // 15-30 minutes cache
                $testimoni = Testimoni::with(['user:id_user,name'])
                    ->where('status', ContentStatus::TERPUBLIKASI->value)
                    ->orderBy('created_at', 'desc')
                    ->get();

                return [
                    'data' => TestimoniResource::collection($testimoni),
                    'cached_at' => now()->toISOString(),
                    'cache_key' => 'testimoni_published'
                ];
            });

            return response()->json([
                'data' => $result['data'],
                'cached_at' => $result['cached_at'],
                'cache_key' => $result['cache_key']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Testimoni',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
