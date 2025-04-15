<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    protected $table = 'testimoni';
    protected $primaryKey = 'id_testimoni';

    protected $fillable = [
        'id_kategori_testimoni',
        'nama',
        'email',
        'foto_profil',
        'status',
        'rating',
        'konten',
    ];

    /**
     * Get the category that owns the testimonial.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriTestimoni::class, 'id_kategori_testimoni', 'id_kategori_testimoni');
    }
}