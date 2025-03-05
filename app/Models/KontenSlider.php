<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontenSlider extends Model
{
    use HasFactory;

    protected $table = 'konten_slider';
    
    protected $fillable = [
        'judul',
        'sub_judul',
        'deskripsi',
        'gambar',
        'tombol_teks',
        'tombol_link',
        'urutan',
        'status',
    ];
}