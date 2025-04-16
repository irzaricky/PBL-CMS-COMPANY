<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'artikel';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_artikel';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_kategori_artikel',
        'id_user',
        'thumbnail_artikel',
        'judul_artikel',
        'konten_artikel',
        'slug',
    ];

    /**
     * Get the category that this article belongs to.
     */
    public function kategoriArtikel()
    {
        return $this->belongsTo(KategoriArtikel::class, 'id_kategori_artikel', 'id_kategori_artikel');
    }

    /**
     * Get the user that authored this article.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the sliders that feature this article.
     */
    public function kontenSliders()
    {
        return $this->hasMany(KontenSlider::class, 'id_artikel', 'id_artikel');
    }
}