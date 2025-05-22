<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use App\Http\Resources\StrukturOrganisasiResource;
use Illuminate\Http\Request;

class StrukturOrganisasiController extends Controller
{
    /**
     * Mengambil daftar struktur organisasi yang aktif
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $struktur = StrukturOrganisasi::with(['user:id_user,name,foto_profil'])
                ->whereNull('tanggal_selesai')
                ->orWhere('tanggal_selesai', '>=', now())
                ->orderBy('jabatan', 'asc')
                ->get();

            return StrukturOrganisasiResource::collection($struktur);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Struktur Organisasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
