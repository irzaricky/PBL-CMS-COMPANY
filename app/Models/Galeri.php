<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';

    protected $fillable = [
        'kategori_id',
        'judul',
        'deskripsi',
        'file_path',
        'jenis_media',
        'status',
    ];

    /**
     * Get the category that owns the gallery item.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriGaleri::class, 'kategori_id');
    }
}