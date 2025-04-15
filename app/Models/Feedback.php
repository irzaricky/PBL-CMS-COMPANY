<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'nama',
        'email',
        'subjek',
        'pesan',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}