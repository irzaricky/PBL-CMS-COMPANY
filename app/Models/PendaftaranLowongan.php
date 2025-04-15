<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranLowongan extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_lowongan';

    protected $fillable = [
        'lowongan_id',
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
    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class);
    }
}