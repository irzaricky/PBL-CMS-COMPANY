<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event';
    protected $primaryKey = 'id_event';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'gambar_cover',
        'waktu_mulai',
        'waktu_akhir',
        'lokasi',
        'link_pendaftaran',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_akhir' => 'datetime',
    ];

    /**
     * Get the registrations for the event.
     */
    public function pendaftaran()
    {
        return $this->hasMany(PendaftaranEvent::class, 'id_event', 'id_event');
    }
}