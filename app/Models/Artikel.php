<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikel';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul',
        'slug',
        'isi',
        'thumbnail',
        'status',
        'tanggal_publikasi',
        'is_highlight',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'datetime',
        'is_highlight' => 'boolean',
    ];

    /**
     * Get the user that owns the article.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the article.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriArtikel::class, 'kategori_id');
    }
}