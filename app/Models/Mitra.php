<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $table = 'mitra';
    
    protected $fillable = [
        'nama_mitra',
        'logo',
        'website',
        'alamat',
        'nomor_telepon',
        'email',
        'jenis_kerjasama',
        'status',
    ];

    /**
     * Get the details for the partner.
     */
    public function detail()
    {
        return $this->hasMany(DetailMitra::class);
    }
}