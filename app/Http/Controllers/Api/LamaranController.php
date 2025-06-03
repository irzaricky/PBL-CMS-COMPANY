<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Http\Resources\LamaranResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class LamaranController extends Controller
{
    /**
     * Menyimpan lamaran baru
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\LamaranResource|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_user' => 'required|exists:users,id_user|integer',
                'id_lowongan' => 'required|exists:lowongan,id_lowongan|integer',
                'surat_lamaran' => 'required|file|mimes:pdf,doc,docx|max:5120',
                'cv' => 'required|file|mimes:pdf,doc,docx|max:2048', 
                'portfolio' => 'nullable|file|mimes:pdf,doc,docx,zip|max:5120',
                'pesan_pelamar' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = [
                'id_user' => $request->id_user,
                'id_lowongan' => $request->id_lowongan,
                'pesan_pelamar' => $request->pesan_pelamar, 
                'status_lamaran' => 'Diproses'
            ];

            // Upload surat lamaran
            if ($request->hasFile('surat_lamaran')) {
                $suratPath = $request->file('surat_lamaran')->store('lamaran/surat-lamaran', 'public');
                $data['surat_lamaran'] = $suratPath;
            }

            // Upload CV jika ada
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('lamaran/cv', 'public');
                $data['cv'] = $cvPath;
            }

            // Upload portfolio jika ada
            if ($request->hasFile('portfolio')) {
                $portfolioPath = $request->file('portfolio')->store('lamaran/portfolio', 'public');
                $data['portfolio'] = $portfolioPath;
            }
            
            $lamaran = Lamaran::create($data);

            return (new LamaranResource($lamaran))
                ->additional([
                    'status' => 'success',
                    'message' => 'Lamaran berhasil dikirim'
                ])->response()->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim lamaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil lamaran berdasarkan user ID
     * 
     * @param int $userId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function getByUserId($userId)
    {
        try {
            $lamaran = Lamaran::with('lowongan')
                ->where('id_user', $userId)
                ->get();

            return LamaranResource::collection($lamaran)
                ->additional([
                    'status' => 'success',
                    'message' => 'Lamaran berhasil diambil'
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data lamaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil detail lamaran berdasarkan ID
     * 
     * @param int $id
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $lamaran = Lamaran::with('lowongan', 'user')
                ->findOrFail($id);

            // if (auth()->check() && auth()->user()->id_user != $lamaran->id_user) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Anda tidak memiliki akses untuk melihat lamaran ini'
            //     ], 403);
            // }

            return (new LamaranResource($lamaran))
                ->additional([
                    'status' => 'success',
                    'message' => 'Detail lamaran berhasil diambil'
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail lamaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
