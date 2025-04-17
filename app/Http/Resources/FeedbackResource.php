<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_feedback' => $this->id_feedback,
            'subjek_feedback' => $this->subjek_feedback,
            'isi_feedback' => $this->isi_feedback,
            'tanggal_feedback' => $this->tanggal_feedback,
            'tanggapan_feedback' => $this->tanggapan_feedback,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => [
                'id_user' => $this->user->id_user,
                'name' => $this->user->name,
            ]
        ];
    }
}