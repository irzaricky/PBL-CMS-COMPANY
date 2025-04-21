<?php

namespace App\Http\Resources\Events;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id_event' => $this->id_event,
            'nama_event' => $this->nama_event,
            'deskripsi_event' => $this->deskripsi_event,
            'thumbnail_event' => $this->thumbnail_event,
            'lokasi_event' => $this->lokasi_event,
            'link_lokasi_event' => $this->link_lokasi_event,
            'waktu_start_event' => $this->waktu_start_event,
            'waktu_end_event' => $this->waktu_end_event,
            'link_daftar_event' => $this->link_daftar_event,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'slug' => $this->slug,
        ];
    }
}