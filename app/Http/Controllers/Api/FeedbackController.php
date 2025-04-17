<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Resources\FeedbackResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display a listing of feedback.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $feedback = Feedback::with('user:id_user,name')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return FeedbackResource::collection($feedback);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Feedback',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * View a single feedback item.
     *
     * @param int $id
     * @return \App\Http\Resources\FeedbackResource|\Illuminate\Http\JsonResponse
     */
    public function view($id)
    {
        try {
            $feedback = Feedback::with('user:id_user,name')
                ->findOrFail($id);

            return new FeedbackResource($feedback);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Feedback Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
