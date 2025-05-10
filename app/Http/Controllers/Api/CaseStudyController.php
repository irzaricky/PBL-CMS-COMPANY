<?php

namespace App\Http\Controllers\Api;

use App\Models\CaseStudy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CaseStudy\CaseStudyViewResource;
use App\Http\Resources\CaseStudy\CaseStudyListResource;

class CaseStudyController extends Controller
{
    /**
     * Mengambil daftar case study
     */
    public function index(Request $request)
    {
        try {
            $query = CaseStudy::with(['mitra'])
                ->where('status_case_study', 'published')
                ->whereNull('deleted_at');

            // Filter by mitra if provided
            if ($request->has('mitra')) {
                $query->where('id_mitra', $request->mitra);
            }

            $caseStudy = $query->paginate(10);

            return CaseStudyListResource::collection($caseStudy);
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
            $caseStudy = CaseStudy::with(['mitra'])
                ->where('case_study_id', $id)
                ->where('status_case_study', 'published')
                ->whereNull('deleted_at')
                ->firstOrFail();

            return new CaseStudyViewResource($caseStudy);
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
            $caseStudy = CaseStudy::where('slug_case_study', $slug)
                ->where('status_case_study', 'published')
                ->whereNull('deleted_at')
                ->firstOrFail();

            return new CaseStudyViewResource($caseStudy);
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

        $CaseStudyQuery = CaseStudy::with(['mitra'])
            ->where('status_case_study', 'published')
            ->whereNull('deleted_at');

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

        return CaseStudyListResource::collection($caseStudies);
    }
}
