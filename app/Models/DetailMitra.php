<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailMitra extends Model
{
    use HasFactory;

    protected $table = 'detail_mitra';

    protected $fillable = [
        'mitra_id',
        'tanggal_mulai_kerjasama',
        'tanggal_akhir_kerjasama',
        'deskripsi_kerjasama',
        'nilai_kontrak',
        'status_kerjasama',
        'dokumen',
    ];

    protected $casts = [
        'tanggal_mulai_kerjasama' => 'datetime',
        'tanggal_akhir_kerjasama' => 'datetime',
        'nilai_kontrak' => 'decimal:2',
    ];

    /**
     * Get the mitra that owns the detail.
     */
    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }
}