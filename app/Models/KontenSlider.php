<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontenSlider extends Model
{
    use HasFactory;

    protected $table = 'konten_slider';
    protected $primaryKey = 'id_konten_slider';

    protected $fillable = [
        'nama',
        'deskripsi',
        'lokasi_file',
        'tipe_media',
        'link',
        'status'
    ];
}