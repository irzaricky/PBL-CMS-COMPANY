<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'profil_perusahaan';
    protected $primaryKey = 'id_profil_perusahaan';

    protected $fillable = [
        'nama_perusahaan',
        'deskripsi_perusahaan',
        'visi',
        'misi',
        'sejarah',
        'pencapaian_perusahaan',
        'alamat_perusahaan',
        'email_perusahaan',
        'nomor_telepon_perusahaan',
    ];
}