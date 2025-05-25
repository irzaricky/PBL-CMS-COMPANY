<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use App\Http\Resources\StrukturOrganisasiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StrukturOrganisasiController extends Controller
{
    /**
     * Mengambil daftar struktur organisasi yang aktif
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $cacheKey = 'struktur_organisasi.index';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 1800; // 30 minutes - organizational structure rarely changes

            $struktur = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 1800);
                return StrukturOrganisasi::with(['user:id_user,name,foto_profil'])
                    ->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', now())
                    ->orderBy('jabatan', 'asc')
                    ->get();
            });

            $response = StrukturOrganisasiResource::collection($struktur);

            // Add cache info for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Struktur Organisasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
