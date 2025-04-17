<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;

class EventController extends Controller
{
    /**
     * Get all events
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $events = Event::orderBy('waktu_start_event', 'desc')
                ->paginate(10);
            return EventResource::collection($events);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal Memuat Event',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get a single event by ID
     * 
     * @param int $id
     * @return \App\Http\Resources\EventResource|\Illuminate\Http\JsonResponse
     */
    public function view($id)
    {
        try {
            $event = Event::findOrFail($id);
            return new EventResource($event);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event Tidak Ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}