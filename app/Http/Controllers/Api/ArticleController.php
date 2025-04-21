<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Http\Resources\Articles\ArticleListResource;
use App\Http\Resources\Articles\ArticleViewResource;

class ArticleController extends Controller
{
    /**
     * Get all articles
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $articles = Artikel::with(['kategoriArtikel', 'user:id_user,name'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return ArticleListResource::collection($articles);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Artikel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single article by slug
     * 
     * @param string $slug
     * @return \App\Http\Resources\Articles\ArticleViewResource|\Illuminate\Http\JsonResponse
     */
    public function getArticleBySlug($slug)
    {
        try {
            $article = Artikel::with(['kategoriArtikel', 'user:id_user,name'])
                ->where('slug', $slug)
                ->firstOrFail();

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
     * Get the article by id
     * 
     * @param int $id
     * @return \App\Http\Resources\Articles\ArticleViewResource|\Illuminate\Http\JsonResponse
     */
    public function getArticleById($id)
    {
        try {
            $article = Artikel::with(['kategoriArtikel', 'user:id_user,name'])
                ->where('id_artikel', $id)
                ->firstOrFail();

            return new ArticleViewResource($article);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artikel Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
