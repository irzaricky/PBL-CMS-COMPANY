<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimoniArtikel extends Model
{
    protected $table = 'testimoni_artikel';
    protected $primaryKey = 'id_testimoni_artikel';

    protected $fillable = [
        'id_user',
        'id_artikel',
        'isi_testimoni',
        'rating',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'id_artikel');
    }
}
