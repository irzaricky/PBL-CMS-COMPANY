<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magang extends Model
{
    use HasFactory;

    protected $table = 'magang';
    
    protected $fillable = [
        'judul',
        'deskripsi',
        'persyaratan',
        'lokasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    /**
     * Get the registrations for the internship.
     */
    public function pendaftaran()
    {
        return $this->hasMany(PendaftaranMagang::class);
    }
}