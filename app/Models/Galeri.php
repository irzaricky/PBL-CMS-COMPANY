<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'galeri';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_galeri';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_user',
        'id_kategori_galeri',
        'judul_galeri',
        'visualisasi_galeri',
        'deskripsi_galeri',
    ];
}