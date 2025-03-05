<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'profil_perusahaan';
    
    protected $fillable = [
        'nama_perusahaan',
        'logo',
        'alamat',
        'telepon',
        'email',
        'visi',
        'misi',
        'sejarah',
        'slogan',
        'jam_operasional',
    ];
}