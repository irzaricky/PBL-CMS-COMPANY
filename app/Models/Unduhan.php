<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unduhan extends Model
{
    use HasFactory;

    protected $table = 'unduhan';
    
    protected $fillable = [
        'kategori_id',
        'judul',
        'deskripsi',
        'file_path',
        'ukuran_file',
        'format_file',
        'jumlah_unduhan',
        'status',
    ];

    /**
     * Get the category that owns the download.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriUnduhan::class, 'kategori_id');
    }
}