<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'nama_event',
        'deskripsi',
        'lokasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'biaya',
        'kapasitas',
        'gambar',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'biaya' => 'decimal:2',
    ];

    /**
     * Get the registrations for the event.
     */
    public function pendaftaran()
    {
        return $this->hasMany(PendaftaranEvent::class);
    }
}