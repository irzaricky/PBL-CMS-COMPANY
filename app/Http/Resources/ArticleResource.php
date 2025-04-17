<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id_artikel' => $this->id_artikel,
            'judul_artikel' => $this->judul_artikel,
            'konten_artikel' => $this->konten_artikel,
            'thumbnail_artikel' => $this->thumbnail_artikel,
            'created_at' => $this->created_at,
            'kategoriArtikel' => [
                'id_kategori_artikel' => $this->kategoriArtikel->id_kategori_artikel,
                'nama_kategori_artikel' => $this->kategoriArtikel->nama_kategori_artikel,
            ],
            'user' => [
                'id_user' => $this->user->id_user,
                'name' => $this->user->name,
            ],
            'slu    g' => $this->slug,
        ];
    }
}