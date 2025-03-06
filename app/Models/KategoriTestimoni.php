<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriTestimoni extends Model
{
    use HasFactory;

    protected $table = 'kategori_testimoni';
    protected $primaryKey = 'id_kategori_testimoni';

    protected $fillable = [
        'nama',
        'deskripsi',
        'slug',
    ];

    /**
     * Get the testimonials for the category.
     */
    public function testimoni()
    {
        return $this->hasMany(Testimoni::class, 'id_kategori_testimoni', 'id_kategori_testimoni');
    }
}