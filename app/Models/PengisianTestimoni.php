<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengisianTestimoni extends Model
{
    use HasFactory;

    protected $table = 'pengisian_testimoni';

    protected $fillable = [
        'id_testimoni',
        'id_users',
        'tanggal_pengisian',
        'status',
    ];

    protected $casts = [
        'tanggal_pengisian' => 'datetime',
        'status' => 'boolean',
    ];

    /**
     * Get the testimonial associated with the filling.
     */
    public function testimoni()
    {
        return $this->belongsTo(Testimoni::class, 'id_testimoni');
    }

    /**
     * Get the user who filled the testimonial.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}