<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'kategori_produk';
    
    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
    ];

    /**
     * Get the products for the category.
     */
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}