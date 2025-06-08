<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimoniEvent extends Model
{
    protected $table = 'testimoni_event';
    protected $primaryKey = 'id_testimoni_event';

    protected $fillable = [
        'id_user',
        'id_event',
        'isi_testimoni',
        'rating',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
}
