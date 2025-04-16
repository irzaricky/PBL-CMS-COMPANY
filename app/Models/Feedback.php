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
        'isi_feedback',
        'tanggal_feedback',
        'tanggapan_feedback',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_feedback' => 'date',
    ];
}