<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

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

    /**
     * Get the user that owns this gallery item.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the category that this gallery item belongs to.
     */
    public function kategoriGaleri()
    {
        return $this->belongsTo(KategoriGaleri::class, 'id_kategori_galeri', 'id_kategori_galeri');
    }

    /**
     * Get the sliders that feature this gallery item.
     */
    public function kontenSliders()
    {
        return $this->hasMany(KontenSlider::class, 'id_galeri', 'id_galeri');
    }

    /**
     * Get the company profiles that use this gallery.
     */
    public function profilPerusahaan()
    {
        return $this->hasMany(ProfilPerusahaan::class, 'id_galeri', 'id_galeri');
    }
}