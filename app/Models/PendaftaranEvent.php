<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranEvent extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_event';

    protected $fillable = [
        'id_event',
        'id_users',
        'tanggal_registrasi',
        'status_registrasi',
    ];

    protected $casts = [
        'tanggal_registrasi' => 'datetime',
        'status_registrasi' => 'boolean',
    ];

    /**
     * Get the event associated with the registration.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }

    /**
     * Get the user who registered for the event.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}