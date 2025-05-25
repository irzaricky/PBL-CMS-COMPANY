<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\MediaSosial;
use App\Http\Resources\MediaSosialResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MediaSosialController extends Controller
{
    /**
     * Mengambil daftar media sosial yang terpublikasi
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $cacheKey = 'media_sosial.index';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 1800; // 30 minutes - social media links rarely change

            $mediaSosial = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 1800);
                return MediaSosial::where('status', ContentStatus::TERPUBLIKASI->value)->get();
            });

            $response = MediaSosialResource::collection($mediaSosial);

            // Add cache info for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Media Sosial',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
