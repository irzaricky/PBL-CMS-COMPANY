<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContentStatus;
use App\Models\CaseStudy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CaseStudy\CaseStudyViewResource;
use App\Http\Resources\CaseStudy\CaseStudyListResource;
use Illuminate\Support\Facades\Cache;

class CaseStudyController extends Controller
{
    /**
     * Mengambil daftar case study
     */
    public function index(Request $request)
    {
        try {
            // Create cache key based on request parameters
            $cacheKey = 'case_studies_list_' . md5(json_encode($request->all()) . '_page_' . ($request->get('page', 1)));
            $result = Cache::flexible($cacheKey, [600, 1200], function () use ($request) { // 10-20 minutes cache
                $query = CaseStudy::with(['mitra'])
                    ->where('status_case_study', ContentStatus::TERPUBLIKASI);

                // Filter by mitra if provided
                if ($request->has('mitra')) {
                    $query->where('id_mitra', $request->mitra);
                }

                $caseStudy = $query->paginate(10);

                return [
                    'data' => CaseStudyListResource::collection($caseStudy),
                    'cached_at' => now()->toISOString(),
                    'cache_key' => 'case_studies_list'
                ];
            });

            return response()->json([
                'data' => $result['data'],
                'cached_at' => $result['cached_at'],
                'cache_key' => $result['cache_key']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Case Study',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil case study berdasarkan id
     */
    public function getCaseStudyById($id)
    {
        try {
            $cacheKey = 'case_study_by_id_' . $id;

            $result = Cache::flexible($cacheKey, [1800, 3600], function () use ($id) { // 30-60 minutes cache
                $caseStudy = CaseStudy::with(['mitra'])
                    ->where('case_study_id', $id)
                    ->where('status_case_study', ContentStatus::TERPUBLIKASI)
                    ->firstOrFail();

                return [
                    'data' => new CaseStudyViewResource($caseStudy),
                    'cached_at' => now()->toISOString(),
                    'cache_key' => 'case_study_by_id'
                ];
            });

            return response()->json([
                'data' => $result['data'],
                'cached_at' => $result['cached_at'],
                'cache_key' => $result['cache_key']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Case Study',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil case study berdasarkan slug
     */
    public function getCaseStudyBySlug($slug)
    {
        try {
            $cacheKey = 'case_study_by_slug_' . $slug;

            $result = Cache::flexible($cacheKey, [1800, 3600], function () use ($slug) { // 30-60 minutes cache
                $caseStudy = CaseStudy::where('slug_case_study', $slug)
                    ->where('status_case_study', ContentStatus::TERPUBLIKASI)
                    ->firstOrFail();

                return [
                    'data' => new CaseStudyViewResource($caseStudy),
                    'cached_at' => now()->toISOString(),
                    'cache_key' => 'case_study_by_slug'
                ];
            });

            return response()->json([
                'data' => $result['data'],
                'cached_at' => $result['cached_at'],
                'cache_key' => $result['cache_key']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Case Study',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mencari case study berdasarkan judul atau serta mitra
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $mitraId = $request->input('mitra_id');

        // validasi input
        if (empty($query) && empty($mitraId)) {
            return $this->index($request);
        }

        // Create cache key for search
        $cacheKey = 'case_study_search_' . md5($query . '_' . $mitraId . '_page_' . ($request->get('page', 1)));

        $result = Cache::flexible($cacheKey, [300, 600], function () use ($request, $query, $mitraId) { // 5-10 minutes cache for search
            $CaseStudyQuery = CaseStudy::with(['mitra'])
                ->where('status_case_study', ContentStatus::TERPUBLIKASI);

            // Jika ada query pencarian, artikel akan dicari berdasarkan judul
            if (!empty($query)) {
                $CaseStudyQuery->where(function ($q) use ($query) {
                    $q->where('judul_case_study', 'LIKE', "%{$query}%");
                });
            }

            // Jika ada mitraId, case study akan dicari berdasarkan mitra
            if (!empty($mitraId)) {
                $CaseStudyQuery->where('id_mitra', $mitraId);
            }

            $caseStudies = $CaseStudyQuery->orderBy('created_at', 'desc')->paginate(10);

            return [
                'data' => CaseStudyListResource::collection($caseStudies),
                'cached_at' => now()->toISOString(),
                'cache_key' => 'case_study_search'
            ];
        });

        return response()->json([
            'data' => $result['data'],
            'cached_at' => $result['cached_at'],
            'cache_key' => $result['cache_key']
        ]);
    }
}
