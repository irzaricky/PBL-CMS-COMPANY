<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontenSlider extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'konten_slider';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_konten_slider';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_user',
        'id_galeri',
        'id_produk',
        'id_lowongan',
        'id_event',
        'id_artikel',
        'judul_header',
    ];
}