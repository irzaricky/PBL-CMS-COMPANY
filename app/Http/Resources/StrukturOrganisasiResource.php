<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StrukturOrganisasiResource extends JsonResource
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
            // 'id_struktur_organisasi' => $this->id_struktur_organisasi,
            'user' => [
                'id_user' => $this->user->id_user,
                'name' => $this->user->name,
                'foto_profil' => $this->user->foto_profil,
            ],
            'jabatan' => $this->jabatan,
            'deskripsi' => $this->deskripsi,
            'tanggal_mulai' => $this->tanggal_mulai ? $this->tanggal_mulai->format('Y-m-d') : null,
            'tanggal_selesai' => $this->tanggal_selesai ? $this->tanggal_selesai->format('Y-m-d') : null,
            // 'created_at' => $this->created_at->format('Y-m-d'),
            // 'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
