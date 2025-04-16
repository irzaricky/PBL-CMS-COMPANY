<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unduhan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'unduhan';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_unduhan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_kategori_unduhan',
        'id_user',
        'nama_unduhan',
        'slug',
        'lokasi_file',
        'deskripsi',
        'jumlah_unduhan'
    ];

}