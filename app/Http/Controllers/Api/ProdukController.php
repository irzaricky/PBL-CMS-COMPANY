<?php 

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProdukResource;

class ProdukController extends Controller
{
    // Menampilkan daftar produk
    public function index()
    {
        $produk = Produk::with('kategoriProduk')->latest()->paginate(10);
        return ProdukResource::collection($produk);
    }

    // Menampilkan produk berdasarkan ID
    public function show($id)
    {
        $produk = Produk::with('kategoriProduk')->findOrFail($id);
        return new ProdukResource($produk);
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
