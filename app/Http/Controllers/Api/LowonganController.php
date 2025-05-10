<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use App\Http\Resources\Lowongan\LowonganListResource;
use App\Http\Resources\Lowongan\LowonganViewResource;
use Illuminate\Support\Facades\DB;

class LowonganController extends Controller
{
    /**
     * Mengambil daftar lowongan
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $lowongan = Lowongan::whereNull('deleted_at')->orderBy('created_at', 'desc')->paginate(10);

            return LowonganListResource::collection($lowongan);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Lowongan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil lowongan terbaru
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMostRecentLowongan()
    {
        try {
            $lowongan = Lowongan::whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->take(1)
                ->get();

            return LowonganListResource::collection($lowongan);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Lowongan',
                'error' => $e->getMessage()
            ], 500);
        }

    }
}
