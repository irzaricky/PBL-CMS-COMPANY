<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ProfilPerusahaan;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfilPerusahaan\ProfilPerusahaanViewResource;
use App\Http\Resources\ProfilPerusahaan\ProfilPerusahaanNavbarResource;
use Illuminate\Support\Facades\Cache;

class ProfilPerusahaanController extends Controller
{

    /**
     * Mengambil profil perusahaan
     * 
     * @param string Request $request
     * @return \App\Http\Resources\ProfilPerusahaan\ProfilPerusahaanViewResource|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $cacheKey = 'profil_perusahaan.index';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 1800; // 30 minutes - company profile rarely changes

            $profilPerusahaan = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 1800);
                return ProfilPerusahaan::query()->firstOrFail();
            });

            $response = new ProfilPerusahaanViewResource($profilPerusahaan);

            // Add cache info for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profil Perusahaan tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mengambil profil perusahaan untuk navbar
     * 
     * @param string Request $request
     * @return \App\Http\Resources\ProfilPerusahaan\ProfilPerusahaanNavbarResource|\Illuminate\Http\JsonResponse
     */
    public function getDataNavbar(Request $request)
    {
        try {
            $cacheKey = 'profil_perusahaan.navbar';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 1800; // 30 minutes - navbar data rarely changes

            $profilPerusahaan = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 1800);
                return ProfilPerusahaan::query()->firstOrFail();
            });

            $response = new ProfilPerusahaanNavbarResource($profilPerusahaan);

            // Add cache info for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profil Perusahaan tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
