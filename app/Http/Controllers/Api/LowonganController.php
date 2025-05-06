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
     * Get all job vacancies
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $lowongan = Lowongan::orderBy('created_at', 'desc')->paginate(10);

        return LowonganListResource::collection($lowongan);
    }

    /**
     * Get upcoming job vacancies
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMostRecentLowongan()
    {
        try {
            $today = now()->format('Y-m-d');
            $lowongan = Lowongan::orderBy('created_at', 'desc')
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
