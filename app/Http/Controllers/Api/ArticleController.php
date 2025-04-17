<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    /**
     * Get all articles
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $articles = Artikel::with(['kategoriArtikel', 'user:id_user,name'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return ArticleResource::collection($articles);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Artikel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single article by ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function view($id)
    {
        try {
            $article = Artikel::with(['kategoriArtikel', 'user:id_user,name'])->findOrFail($id);
            return new ArticleResource($article);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artikel Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
