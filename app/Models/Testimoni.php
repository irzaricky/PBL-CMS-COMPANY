<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    protected $table = 'testimoni';
    
    protected $fillable = [
        'judul',
        'deskripsi',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    /**
     * Get the pengisian testimoni for the testimoni.
     */
    public function pengisian()
    {
        return $this->hasMany(PengisianTestimoni::class);
    }
}