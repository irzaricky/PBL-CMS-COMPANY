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
     * Menampilkan daftar produk
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
     * Menampilkan produk berdasarkan slug
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
     * Menampilkan produk berdasarkan ID
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

    // Membuat produk baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori_produk' => 'required|exists:kategori_produk,id_kategori_produk',
            'nama_produk' => 'required|string|max:100',
            'thumbnail_produk' => 'nullable|array',
            'harga_produk' => 'required|string|max:50',
            'slug' => 'required|string|unique:produk,slug|max:100',
            'deskripsi_produk' => 'nullable|string',
        ]);

        $produk = Produk::create($validated);
        return new ProdukResource($produk);
    }

    // Memperbarui data produk
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'id_kategori_produk' => 'required|exists:kategori_produk,id_kategori_produk',
            'nama_produk' => 'required|string|max:100',
            'thumbnail_produk' => 'nullable|array',
            'harga_produk' => 'required|string|max:50',
            'slug' => 'required|string|max:100|unique:produk,slug,' . $produk->id_produk,
            'deskripsi_produk' => 'nullable|string',
        ]);

        $produk->update($validated);
        return new ProdukResource($produk);
    }

    // Menghapus produk
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return response()->json(null, 204);
    }
}
