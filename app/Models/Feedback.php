<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedback';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_feedback';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_user',
        'subjek_feedback',
        'tingkat_kepuasan',
        'isi_feedback',
        'tanggapan_feedback',
        'status_feedback',
    ];

    /**
     * Get the user that created this feedback.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}