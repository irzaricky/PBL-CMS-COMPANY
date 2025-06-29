<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\User;
use App\Http\Resources\LamaranResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Notifications\LamaranSubmissionNotification; // Add this import

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

            // Cek apakah user sudah pernah melamar untuk lowongan ini
            $existingApplication = Lamaran::where('id_user', auth()->id())
                ->where('id_lowongan', $request->id_lowongan)
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah pernah melamar untuk lowongan ini'
                ], 422);
            }

            $data = [
                'id_user' => auth()->id(),
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

            // Send notification to user
            $lowongan = Lowongan::findOrFail($request->id_lowongan);
            $user = auth()->user();
            $user->notify(new LamaranSubmissionNotification($lamaran, $lowongan));

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
                ->orderBy('created_at', 'desc') // Urutkan berdasarkan yang terbaru
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

    /**
     * Mengecek apakah user sudah melamar untuk lowongan tertentu
     * 
     * @param int $userId
     * @param int $lowonganId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUserApplication($userId, $lowonganId)
    {
        try {
            $application = Lamaran::with('lowongan')
                ->where('id_user', $userId)
                ->where('id_lowongan', $lowonganId)
                ->orderBy('created_at', 'desc') // Ambil yang terbaru jika ada duplikasi
                ->first();

            if ($application) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User sudah pernah melamar',
                    'data' => new LamaranResource($application),
                    'has_applied' => true
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User belum pernah melamar',
                    'data' => null,
                    'has_applied' => false
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengecek status lamaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
