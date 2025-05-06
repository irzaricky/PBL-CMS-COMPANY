<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ProfilPerusahaan;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfilPerusahaanResource;

class ProfilPerusahaanController extends Controller
{
    /**
     * Get profil perusahaan
     * 
     * @param string Request $request
     * @return \App\Http\Resources\ProfilPerusahaanResource|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $ProfilPerusahaan = ProfilPerusahaan::query()->firstOrFail();

            return new ProfilPerusahaanResource($ProfilPerusahaan);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profil Perusahaan tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
