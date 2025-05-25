<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Http\Resources\FeedbackResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class FeedbackController extends Controller
{
    /**
     * Menyimpan feedback baru
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\FeedbackResource|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_user' => 'required|exists:users,id_user|integer',
                'subjek_feedback' => 'required|string|max:200',
                'isi_feedback' => 'required|string',
                'tingkat_kepuasan' => 'required|integer|min:1|max:5', // Tambahan validasi
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $feedback = Feedback::create([
                'id_user' => $request->id_user,
                'subjek_feedback' => $request->subjek_feedback,
                'isi_feedback' => $request->isi_feedback,
                'tingkat_kepuasan' => $request->tingkat_kepuasan, // Tambahkan ini
            ]);

            // Clear feedback list cache when new feedback is added
            Cache::forget('feedback_list');

            return (new FeedbackResource($feedback))
                ->additional([
                    'status' => 'success',
                    'message' => 'Feedback berhasil dikirim',
                ])->response()->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim feedback',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $cacheKey = 'feedback_list';

        $result = Cache::flexible($cacheKey, [180, 360], function () { // 3-6 minutes cache
            $feedback = Feedback::with('user:id_user,name,foto_profil,email')
                ->orderBy('created_at', 'desc')
                ->get();

            return [
                'data' => FeedbackResource::collection($feedback),
                'cached_at' => now()->toISOString(),
                'cache_key' => 'feedback_list'
            ];
        });

        return response()->json([
            'data' => $result['data'],
            'cached_at' => $result['cached_at'],
            'cache_key' => $result['cache_key']
        ]);
    }
}
