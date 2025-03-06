<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    use HasFactory;

    protected $table = 'lowongan';
    protected $primaryKey = 'id_lowongan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'manfaat',
        'persyaratan',
        'durasi_lowongan',
        'waktu_dibuka',
        'waktu_ditutup',
    ];

    protected $casts = [
        'waktu_dibuka' => 'datetime',
        'waktu_ditutup' => 'datetime',
    ];
}