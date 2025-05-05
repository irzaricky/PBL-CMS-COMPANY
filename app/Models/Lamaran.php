<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lamaran extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lamaran';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_lamaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_user',
        'id_lowongan',
        'nama_asli',
        'cv',
        'portfolio',
        'status_lamaran',
    ];

    /**
     * Get the user that submitted this job application.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the job vacancy that this application is for.
     */
    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'id_lowongan', 'id_lowongan');
    }
}