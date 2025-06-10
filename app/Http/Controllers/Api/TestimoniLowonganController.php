<?php

namespace App\Http\Controllers\Api;

use App\Models\Testimoni;
use App\Enums\ContentStatus;
use Illuminate\Http\Request;
use App\Models\TestimoniLowongan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TestimoniLowonganResource;

class TestimoniLowonganController extends Controller
{
    // Dapatkan semua testimoni untuk lowongan tertentu (publikasi saja)

    public function index($lowonganId)
    {
        $testimoni = TestimoniLowongan::with('user:id_user,name,foto_profil,email')
            ->where('id_lowongan', $lowonganId)
            ->where('status', 'Terpublikasi')
            ->orderBy('created_at', 'desc')
            ->get();

        return TestimoniLowonganResource::collection($testimoni);
    }
    public function store(Request $request, $lowonganId)
    {
        $request->validate([
            'isi_testimoni' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'id_user' => 'required|exists:users,id_user',
        ]);

        $testimoni = new TestimoniLowongan();
        $testimoni->id_lowongan = $lowonganId;
        $testimoni->isi_testimoni = $request->isi_testimoni;
        $testimoni->rating = $request->rating;
        $testimoni->id_user = $request->id_user;
        $testimoni->status = 'Terpublikasi';
        $testimoni->save();

        return response()->json(['message' => 'Testimoni berhasil dikirim!']);
    }
}
