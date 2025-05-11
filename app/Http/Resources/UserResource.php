<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'email'              => $this->email,
            'foto_profil'        => $this->foto_profil,
            'alamat'             => $this->alamat,
            'no_hp'              => $this->no_hp,
            'nik'                => $this->nik,
            'tanggal_lahir'      => $this->tanggal_lahir,
            'status_kepegawaian' => $this->status_kepegawaian,
            'status'             => $this->status,
        ];
    }
}
