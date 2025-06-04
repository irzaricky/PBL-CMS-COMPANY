<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use Illuminate\Http\Request;

class LowonganController extends Controller
{
    /**
     * Mengambil daftar lowongan
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 6);
            $lowongan = Lowongan::orderBy('tanggal_dibuka', 'desc')->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data lowongan berhasil diambil',
                'data' => $lowongan->items(),
                'meta' => [
                    'current_page' => $lowongan->currentPage(),
                    'last_page' => $lowongan->lastPage(),
                    'per_page' => $lowongan->perPage(),
                    'total' => $lowongan->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data lowongan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mencari lowongan berdasarkan query
     * Implementasi sederhana untuk menghindari error
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            
            $lowonganQuery = Lowongan::where(function($q) use ($query) {
                $q->where('judul_lowongan', 'LIKE', "%{$query}%")
                  ->orWhere('deskripsi_pekerjaan', 'LIKE', "%{$query}%");
                  // Hapus bagian lokasi jika kolom tidak ada
                  // ->orWhere('lokasi', 'LIKE', "%{$query}%");
            });
            
            $lowongan = $lowonganQuery->paginate(10);
            
            // Return response yang sama seperti endpoint lain
            return response()->json($lowongan);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mencari lowongan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil detail lowongan berdasarkan slug
     * 
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLowonganBySlug($slug)
    {
        try {
            $lowongan = Lowongan::where('slug', $slug)->first();
            
            if (!$lowongan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lowongan tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Detail lowongan berhasil diambil',
                'data' => $lowongan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail lowongan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}