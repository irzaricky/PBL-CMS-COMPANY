<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaSosial extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media_sosial';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_media_sosial';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_media_sosial',
        'icon',
        'link',
        'status'
    ];
}