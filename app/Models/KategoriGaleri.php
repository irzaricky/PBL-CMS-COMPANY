<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriGaleri extends Model
{
    use HasFactory;

    protected $table = 'kategori_galeri';
    
    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
    ];

    /**
     * Get the gallery items for the category.
     */
    public function galeri()
    {
        return $this->hasMany(Galeri::class, 'kategori_id');
    }
}