<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use App\Http\Resources\Lowongan\LowonganListResource;
use App\Http\Resources\Lowongan\LowonganViewResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LowonganController extends Controller
{
    /**
     * Mengambil daftar lowongan
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $cacheKey = 'lowongan.index';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 180; // 3 minutes

            $lowongan = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 180);
                return Lowongan::where('status_lowongan', ContentStatus::TERPUBLIKASI->value)
                    ->where('tanggal_dibuka', '<=', now())
                    ->where('tanggal_ditutup', '>=', now())
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            });

            $response = LowonganListResource::collection($lowongan);

            // Add cache info for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Lowongan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil lowongan terbaru
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMostRecentLowongan()
    {
        try {
            $cacheKey = 'lowongan.most_recent';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 300; // 5 minutes

            $lowongan = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 300);
                return Lowongan::where('status_lowongan', ContentStatus::TERPUBLIKASI->value)
                    ->where('tanggal_dibuka', '<=', now())
                    ->where('tanggal_ditutup', '>=', now())
                    ->orderBy('created_at', 'desc')
                    ->take(1)
                    ->get();
            });

            $response = LowonganListResource::collection($lowongan);

            // Add timestamp for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Lowongan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil lowongan berdasarkan slug
     * 
     * @param string $slug
     * @return \App\Http\Resources\Lowongan\LowonganViewResource|\Illuminate\Http\JsonResponse
     */
    public function getLowonganBySlug($slug)
    {
        try {
            $lowongan = Lowongan::with(['user:id_user,name'])
                ->where('status_lowongan', ContentStatus::TERPUBLIKASI->value)
                ->where('slug', $slug)
                ->firstOrFail();

            return new LowonganViewResource($lowongan);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lowongan Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mengambil lowongan berdasarkan ID
     * 
     * @param int $id
     * @return \App\Http\Resources\Lowongan\LowonganViewResource|\Illuminate\Http\JsonResponse
     */
    public function getLowonganById($id)
    {
        try {
            $lowongan = Lowongan::with(['user:id_user,name'])
                ->where('status_lowongan', ContentStatus::TERPUBLIKASI->value)
                ->findOrFail($id);

            return new LowonganViewResource($lowongan);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lowongan Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mencari lowongan berdasarkan judul atau jenis
     * 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            $jenisLowongan = $request->input('jenis_lowongan');

            // validasi input, jika tidak ada query dan jenis_lowongan maka kembalikan semua lowongan
            if (empty($query) && empty($jenisLowongan)) {
                return $this->index();
            }

            $lowonganQuery = Lowongan::with(['user:id_user,name'])
                ->where('status_lowongan', ContentStatus::TERPUBLIKASI->value)
                ->where('tanggal_dibuka', '<=', now())
                ->where('tanggal_ditutup', '>=', now());

            // Jika ada query pencarian, lowongan akan dicari berdasarkan judul
            if (!empty($query)) {
                $lowonganQuery->where(function ($q) use ($query) {
                    $q->where('judul_lowongan', 'LIKE', '%' . $query . '%')
                        ->orWhere('deskripsi_pekerjaan', 'LIKE', '%' . $query . '%');
                });
            }

            // Jika ada jenis_lowongan, lowongan akan dicari berdasarkan jenis
            if (!empty($jenisLowongan)) {
                $lowonganQuery->where('jenis_lowongan', $jenisLowongan);
            }

            $lowongan = $lowonganQuery->orderBy('created_at', 'desc')->paginate(10);

            // Check if no lowongan were found
            if ($lowongan->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tidak ada lowongan yang sesuai dengan pencarian',
                    'data' => []
                ], 200);
            }

            return LowonganListResource::collection($lowongan);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mencari lowongan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
