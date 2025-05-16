<?php

namespace App\Http\Controllers\Api;

use App\Models\Testimoni;
use App\Enums\ContentStatus;
use Illuminate\Http\Request;
use App\Models\TestimoniProduk;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TestimoniProdukResource;

class TestimoniProdukController extends Controller
{
    // Dapatkan semua testimoni untuk produk tertentu (publikasi saja)

    public function index($produkId)
    {
        $testimoni = TestimoniProduk::with('user:id_user,name,foto_profil,email')
            ->where('id_produk', $produkId)
            ->where('status', 'Terpublikasi')
            ->orderBy('created_at', 'desc')
            ->get();

        return TestimoniProdukResource::collection($testimoni);
    }
    public function store(Request $request, $produkId)
    {
        $request->validate([
            'isi_testimoni' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'id_user' => 'required|exists:users,id_user',
        ]);

        $testimoni = new TestimoniProduk();
        $testimoni->id_produk = $produkId;
        $testimoni->isi_testimoni = $request->isi_testimoni;
        $testimoni->rating = $request->rating;
        $testimoni->id_user = $request->id_user;
        $testimoni->status = 'Terpublikasi';
        $testimoni->save();

        return response()->json(['message' => 'Testimoni berhasil dikirim!']);
    }
}
