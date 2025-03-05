<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranMagang extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_magang';
    
    protected $fillable = [
        'magang_id',
        'nama',
        'email',
        'nomor_telepon',
        'institusi',
        'cv',
        'surat_pengantar',
        'status',
    ];

    /**
     * Get the internship that owns the registration.
     */
    public function magang()
    {
        return $this->belongsTo(Magang::class);
    }
}