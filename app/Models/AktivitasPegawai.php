<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasPegawai extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_pegawai';

    protected $fillable = [
        'user_id',
        'judul_aktivitas',
        'deskripsi',
        'tanggal',
        'foto_aktivitas',
        'status',
    ];

    /**
     * Get the user that owns the activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}