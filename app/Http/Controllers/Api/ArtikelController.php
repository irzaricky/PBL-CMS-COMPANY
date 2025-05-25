<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Http\Resources\Articles\ArticleListResource;
use App\Http\Resources\Articles\ArticleViewResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ArtikelController extends Controller
{
    /**
     * Mengambil daftar artikel
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
            $cacheKey = "articles.index.category_{$categoryId}.page_{$page}";
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 180; // 3 minutes

            $articles = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($request, $timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 180);

                $query = Artikel::with(['kategoriArtikel', 'user'])
                    ->where('status_artikel', ContentStatus::TERPUBLIKASI)
                    ->orderBy('created_at', 'desc');

                // Filter berdasarkan kategori jika ada parameter category_id
                if ($request->has('category_id')) {
                    $query->where('id_kategori_artikel', $request->category_id);
                }

                return $query->paginate(10);
            });

            $response = ArticleListResource::collection($articles);

            // Add cache info for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Artikel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil artikel berdasarkan slug
     * 
     * @param string $slug
     * @return \App\Http\Resources\Articles\ArticleViewResource|\Illuminate\Http\JsonResponse
     */
    public function getArticleBySlug($slug)
    {
        try {
            $article = Artikel::with(['kategoriArtikel', 'user'])
                ->where('status_artikel', ContentStatus::TERPUBLIKASI)
                ->where('slug', $slug)
                ->firstOrFail();

            $article->increment('jumlah_view');
            return new ArticleViewResource($article);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artikel Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mengambil artikel berdasarkan ID
     * 
     * @param int $id
     * @return \App\Http\Resources\Articles\ArticleViewResource|\Illuminate\Http\JsonResponse
     */
    public function getArticleById($id)
    {
        try {
            $article = Artikel::with(['kategoriArtikel', 'user'])
                ->where('status_artikel', ContentStatus::TERPUBLIKASI)
                ->findOrFail($id);

            return new ArticleViewResource($article);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artikel Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mengambil semua kategori artikel
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories()
    {
        try {
            $cacheKey = 'artikel.categories';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 600; // 10 minutes - categories don't change often

            $categories = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 600);
                return KategoriArtikel::get();
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

    /**
     * Mencari artikel berdasarkan judul atau serta kategori
     * 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            $categoryId = $request->input('category_id');

            // validasi input, jika tidak ada query dan category_id maka kembalikan semua artikel (karna parameter kosong)
            if (empty($query) && empty($categoryId)) {
                return $this->index($request);
            }

            $articlesQuery = Artikel::with(['kategoriArtikel', 'user'])
                ->where('status_artikel', ContentStatus::TERPUBLIKASI);

            // Jika ada query pencarian, artikel akan dicari berdasarkan judul
            if (!empty($query)) {
                $articlesQuery->where(function ($q) use ($query) {
                    $q->where('judul_artikel', 'LIKE', "%{$query}%");
                });
            }

            // Jika ada category_id, artikel akan dicari berdasarkan kategori
            if (!empty($categoryId)) {
                $articlesQuery->where('id_kategori_artikel', $categoryId);
            }

            $articles = $articlesQuery->orderBy('created_at', 'desc')->paginate(10);

            // Add a check if no articles were found
            if ($articles->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tidak ada artikel yang sesuai dengan pencarian',
                    'data' => []
                ], 200);
            }

            return ArticleListResource::collection($articles);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mencari artikel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil artikel dengan jumlah view terbanyak
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getArticleByMostView()
    {
        try {
            $cacheKey = 'articles.most_viewed';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 300; // 5 minutes

            $articles = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 300);
                return Artikel::with(['kategoriArtikel', 'user'])
                    ->where('status_artikel', ContentStatus::TERPUBLIKASI)
                    ->orderBy('jumlah_view', 'desc')
                    ->take(1)
                    ->get();
            });

            $response = ArticleListResource::collection($articles);

            // Add timestamp for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Artikel',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getFeaturedArticles()
    {
        try {
            $articles = Artikel::with(['kategoriArtikel', 'user'])
                ->where('status_artikel', ContentStatus::TERPUBLIKASI)
                ->orderBy('jumlah_view', 'desc')
                ->take(4)
                ->get();

            return ArticleListResource::collection($articles);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Artikel',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
