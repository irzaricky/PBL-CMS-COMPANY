<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontenSlider extends Model
{
    use HasFactory;

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

    /**
     * Relationships
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function galeri()
    {
        return $this->belongsTo(Galeri::class, 'id_galeri', 'id_galeri');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'id_lowongan', 'id_lowongan');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event', 'id_event');
    }

    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'id_artikel', 'id_artikel');
    }
}