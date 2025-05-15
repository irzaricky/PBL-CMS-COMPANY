<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use App\Http\Resources\TestimoniResource;
use Illuminate\Http\Request;

class TestimoniProdukController extends Controller
{
    /**
     * Mengambil daftar testimoni yang terpublikasi
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $testimoni = Testimoni::with(['user:id_user,name'])
                ->where('status', ContentStatus::TERPUBLIKASI->value)
                ->orderBy('created_at', 'desc')
                ->get();

            return TestimoniResource::collection($testimoni);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Testimoni',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
