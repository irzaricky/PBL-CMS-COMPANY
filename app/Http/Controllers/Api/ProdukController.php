<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContentStatus;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProdukResource;
use App\Http\Resources\Produk\ProdukListResource;
use App\Http\Resources\Produk\ProdukViewResource;
use App\Models\KategoriProduk;
use Illuminate\Support\Facades\Cache;

class ProdukController extends Controller
{
    /**
     * Mengambil daftar produk
     * 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        try {
            // Create cache key based on request parameters
            $categoryId = $request->get('category_id');
            $page = $request->get('page', 1);
            $cacheKey = "produk.index.category_{$categoryId}.page_{$page}";
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 300; // 5 minutes

            $produk = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($request, $timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 300);

                $query = Produk::with('kategoriProduk')
                    ->where('status_produk', ContentStatus::TERPUBLIKASI)
                    ->latest();

                // Filter berdasarkan kategori jika ada parameter category_id
                if ($request->has('category_id')) {
                    $query->where('id_kategori_produk', $request->category_id);
                }

                return $query->paginate(9);
            });

            $response = ProdukListResource::collection($produk);

            // Add cache info for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil produk berdasarkan slug
     * 
     * @param string $slug
     * @return \App\Http\Resources\Produk\ProdukViewResource|\Illuminate\Http\JsonResponse
     */
    public function getProdukBySlug($slug)
    {
        try {
            $produk = Produk::with(['kategoriProduk'])
                ->where('slug', $slug)
                ->where('status_produk', ContentStatus::TERPUBLIKASI)
                ->firstOrFail();

            return new ProdukViewResource($produk);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mengambil produk berdasarkan ID
     * 
     * @param int $id
     * @return \App\Http\Resources\Produk\ProdukViewResource|\Illuminate\Http\JsonResponse
     */
    public function getProdukById($id)
    {
        try {
            $produk = Produk::with('kategoriProduk')
                ->where('status_produk', ContentStatus::TERPUBLIKASI)
                ->findOrFail($id);
            return new ProdukViewResource($produk);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mencari produk berdasarkan nama produk atau deskripsi
     * 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            $categoryId = $request->input('category_id');

            // Validasi input, jika tidak ada query dan category_id, kembalikan semua produk
            if (empty($query) && empty($categoryId)) {
                return $this->index($request);
            }

            $produkQuery = Produk::with('kategoriProduk')
                ->where('status_produk', ContentStatus::TERPUBLIKASI);

            // Jika ada query pencarian, produk akan dicari berdasarkan nama atau deskripsi
            if (!empty($query)) {
                $produkQuery->where(function ($q) use ($query) {
                    $q->where('nama_produk', 'LIKE', "%{$query}%")
                        ->orWhere('deskripsi_produk', 'LIKE', "%{$query}%");
                });
            }

            // Jika ada category_id, produk akan dicari berdasarkan kategori
            if (!empty($categoryId)) {
                $produkQuery->where('id_kategori_produk', $categoryId);
            }

            $produk = $produkQuery->orderBy('created_at', 'desc')->paginate(9);

            // Check if no products were found
            if ($produk->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tidak ada produk yang sesuai dengan pencarian',
                    'data' => []
                ], 200);
            }

            return ProdukListResource::collection($produk);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Mencari Produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCategories()
    {
        try {
            $cacheKey = 'produk.categories';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 900; // 15 minutes - categories don't change often

            $categories = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 900);
                return KategoriProduk::get();
            });

            return response()->json([
                'status' => 'success',
                'data' => $categories,
                'cached_at' => Cache::get($timestampKey, now()->toISOString()),
                'cache_key' => $cacheKey
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function latest()
    {
        try {
            $cacheKey = 'produk.latest';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 300; // 5 minutes

            $produk = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 300);
                return Produk::with('kategoriProduk')
                    ->where('status_produk', ContentStatus::TERPUBLIKASI)
                    ->latest() // default: order by created_at desc
                    ->first(); // Ambil satu produk terbaru
            });

            if (!$produk) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Produk Tidak Ditemukan',
                ], 404);
            }

            $response = new ProdukListResource($produk);

            // Add cache info for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil produk terbaru',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
