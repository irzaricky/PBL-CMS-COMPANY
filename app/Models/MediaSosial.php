<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaSosial extends Model
{
    use HasFactory;

    protected $table = 'media_sosial';
    protected $primaryKey = 'id_media_sosial';

    protected $fillable = [
        'nama',
        'link',
        'ikon',
        'status',
    ];
}