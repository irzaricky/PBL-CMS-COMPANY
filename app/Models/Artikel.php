<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'artikel';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_artikel';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_kategori_artikel',
        'id_user',
        'thumbnail_artikel',
        'judul_artikel',
        'konten_artikel',
        'slug',
    ];
}