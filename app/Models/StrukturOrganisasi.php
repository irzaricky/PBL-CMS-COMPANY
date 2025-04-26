<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'struktur_organisasi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_struktur_organisasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_user',
        'deskripsi',
        'thumbnail_struktur_organisasi',
    ];

    /**
     * Get the user associated with the struktur organisasi.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}