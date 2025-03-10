<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Get all articles
     */
    public function index()
    {
        $articles = Artikel::with(['kategori', 'user:id,name'])
            ->orderBy('tanggal_upload', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $articles
        ]);
    }

    /**
     * Get a specific article
     */
    public function show($id)
    {
        $article = Artikel::with(['kategori', 'user:id,name'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $article
        ]);
    }

    /**
     * Get articles by category
     */
    public function getByCategory($categoryId)
    {
        $articles = Artikel::with(['kategori', 'user:id,name'])
            ->where('id_kategori_artikel', $categoryId)
            ->orderBy('tanggal_upload', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $articles
        ]);
    }
}
