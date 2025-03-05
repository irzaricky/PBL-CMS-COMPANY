<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranEvent extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_event';
    
    protected $fillable = [
        'event_id',
        'nama',
        'email',
        'nomor_telepon',
        'institusi',
        'bukti_pembayaran',
        'status',
    ];

    /**
     * Get the event that owns the registration.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}