<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LamaranResource extends JsonResource
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
            'id_lamaran' => $this->id_lamaran,
            'nama_asli' => $this->nama_asli,
            'lowongan' => [
                'id_lowongan' => $this->lowongan->id_lowongan,
                'judul_lowongan' => $this->lowongan->judul_lowongan,
            ],
            'status_lamaran' => $this->status_lamaran,
            'cv' => $this->cv ? url('storage/' . $this->cv) : null,
            'portfolio' => $this->portfolio ? url('storage/' . $this->portfolio) : null,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
