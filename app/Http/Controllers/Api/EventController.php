<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Resources\Events\EventListResource;
use App\Http\Resources\Events\EventViewResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class EventController extends Controller
{
    /**
     * Mengambil daftar event
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $cacheKey = 'events.index';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 180; // 3 minutes

            $events = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 180);
                return Event::where('waktu_start_event', '>', Carbon::now())
                    ->orderBy('waktu_start_event', 'asc')
                    ->paginate(10);
            });

            $response = EventListResource::collection($events);

            // Add cache info for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil daftar event berdasarkan slug
     * 
     * @param string $slug
     * @return \App\Http\Resources\Events\EventViewResource|\Illuminate\Http\JsonResponse
     */
    public function getEventBySlug($slug)
    {
        try {
            $event = Event::where('slug', $slug)
                ->firstOrFail();
            return new EventViewResource($event);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /** 
     * Mengambil daftar event berdasarkan id
     * 
     * @param int $id
     * @return \App\Http\Resources\Events\EventViewResource|\Illuminate\Http\JsonResponse
     */
    public function getEventById($id)
    {
        try {
            $event = Event::findOrFail($id);
            return new EventViewResource($event);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Mengambil event terbaru
     * 
     * @return \App\Http\Resources\Events\EventListResource|\Illuminate\Http\JsonResponse
     */
    public function getMostRecentEvent()
    {
        try {
            $cacheKey = 'event.most_recent';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 600; // 10 minutes - recent event changes infrequently

            $event = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 600);
                return Event::orderBy('waktu_start_event', 'desc')->first();
            });

            $response = new EventListResource($event);

            // Add cache info for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getNavbarRecentEvent()
    {
        try {
            $cacheKey = 'event.navbar_recent';
            $timestampKey = $cacheKey . '.timestamp';
            $cacheDuration = 300; // 5 minutes

            $event = Cache::flexible($cacheKey, [$cacheDuration, $cacheDuration * 2], function () use ($timestampKey) {
                // Store timestamp when cache is created
                Cache::put($timestampKey, now()->toISOString(), 300);
                return Event::orderBy('waktu_start_event', 'desc')->first();
            });

            $response = new EventListResource($event);

            // Add timestamp for testing
            $responseData = $response->response()->getData(true);
            $responseData['cached_at'] = Cache::get($timestampKey, now()->toISOString());
            $responseData['cache_key'] = $cacheKey;

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mencari event berdasarkan judul atau lokasi
     * 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');

            // validasi input, jika tidak ada query maka kembalikan semua event
            if (empty($query)) {
                return $this->index();
            }

            $eventsQuery = Event::where(function ($q) use ($query) {
                $q->where('nama_event', 'LIKE', '%' . $query . '%')
                    ->orWhere('lokasi_event', 'LIKE', '%' . $query . '%')
                    ->orWhere('deskripsi_event', 'LIKE', '%' . $query . '%');
            });

            $events = $eventsQuery->orderBy('waktu_start_event', 'asc')->paginate(10);

            // Check if no events were found
            if ($events->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tidak ada event yang sesuai dengan pencarian',
                    'data' => []
                ], 200);
            }

            return EventListResource::collection($events);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mencari event',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
