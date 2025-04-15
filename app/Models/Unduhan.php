<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unduhan extends Model
{
    use HasFactory;

    protected $table = 'unduhan';
    protected $primaryKey = 'id_unduhan';

    protected $fillable = [
        'id_kategori_unduhan',
        'id_users',
        'nama',
        'slug',
        'lokasi_file',
        'jenis_file',
        'ukuran_file',
        'deskripsi',
        'jumlah_unduhan',
        'status_publikasi',
    ];

    /**
     * Get the category that owns the download.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriUnduhan::class, 'id_kategori_unduhan', 'id_kategori_unduhan');
    }

    /**
     * Get the user that owns the download.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}