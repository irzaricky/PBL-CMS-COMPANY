<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'testimoni';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_testimoni';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_user',
        'isi_testimoni',
        'thumbnail_testimoni',
        'rating',
    ];

    /**
     * Get the user that created this testimonial.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}