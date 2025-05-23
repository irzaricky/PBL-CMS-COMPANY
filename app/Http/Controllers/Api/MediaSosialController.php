<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\MediaSosial;
use App\Http\Resources\MediaSosialResource;
use Illuminate\Http\Request;

class MediaSosialController extends Controller
{
    /**
     * Mengambil daftar media sosial yang terpublikasi
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $mediaSosial = MediaSosial::where('status', ContentStatus::TERPUBLIKASI->value)
                ->get();

            return MediaSosialResource::collection($mediaSosial);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Media Sosial',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
