<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriArtikel extends Model
{
    use HasFactory;

    protected $table = 'kategori_artikel';
    protected $primaryKey = 'id_kategori_artikel';

    protected $fillable = [
        'nama',
        'slug',
    ];

    /**
     * Get the articles for the category.
     */
    public function artikel()
    {
        return $this->hasMany(Artikel::class, 'id_kategori_artikel', 'id_kategori_artikel');
    }
}