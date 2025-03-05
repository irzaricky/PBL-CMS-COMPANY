<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengisianTestimoni extends Model
{
    use HasFactory;

    protected $table = 'pengisian_testimoni';
    
    protected $fillable = [
        'testimoni_id',
        'nama',
        'email',
        'isi_testimoni',
        'rating',
        'status',
    ];

    /**
     * Get the testimoni that owns the pengisian testimoni.
     */
    public function testimoni()
    {
        return $this->belongsTo(Testimoni::class);
    }
}