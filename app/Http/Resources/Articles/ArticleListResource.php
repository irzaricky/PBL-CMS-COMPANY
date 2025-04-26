<?php

namespace App\Http\Resources\Articles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray(Request $request)
    {
        return [
            'id_artikel' => $this->id_artikel,
            'judul_artikel' => $this->judul_artikel,
            // 'konten_artikel' => substr($this->konten_artikel, 0, 100) . '...', tidak usah dikirimkan
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
            'jumlah_view' => $this->jumlah_view,
            'slug' => $this->slug,
        ];
    }
}