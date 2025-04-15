<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriUnduhan extends Model
{
    use HasFactory;

    protected $table = 'kategori_unduhan';
    protected $primaryKey = 'id_kategori_unduhan';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    /**
     * Get the downloads for the category.
     */
    public function unduhan()
    {
        return $this->hasMany(Unduhan::class, 'id_kategori_unduhan', 'id_kategori_unduhan');
    }
}