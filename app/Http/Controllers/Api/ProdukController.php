<?php

namespace App\Http\Controllers\Api;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProdukResource;
use App\Http\Resources\Produk\ProdukListResource;
use App\Http\Resources\Produk\ProdukViewResource;

class ProdukController extends Controller
{
    /**
     * Mengambil daftar produk
     * 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $produk = Produk::with('kategoriProduk')->whereNull('deleted_at')->latest()->paginate(10);
            return ProdukListResource::collection($produk);
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
                ->whereNull('deleted_at')
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
            $produk = Produk::with('kategoriProduk')->whereNull('deleted_at')->findOrFail($id);
            return new ProdukViewResource($produk);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
