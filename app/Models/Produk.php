<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produk';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_produk';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_kategori_produk',
        'nama_produk',
        'gambar_produk',
        'harga_produk',
        'slug',
        'deskripsi_produk',
    ];

    protected $casts = [
        'thumbnail_produk' => 'array',
    ];

    /**
     * Get the category that this product belongs to.
     */
    public function kategoriProduk()
    {
        return $this->belongsTo(KategoriProduk::class, 'id_kategori_produk', 'id_kategori_produk');
    }

    /**
     * Get the sliders that feature this product.
     */
    public function kontenSliders()
    {
        return $this->hasMany(KontenSlider::class, 'id_produk', 'id_produk');
    }
}