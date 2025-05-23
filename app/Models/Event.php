<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_event';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_event',
        'deskripsi_event',
        'thumbnail_event',
        'lokasi_event',
        'link_lokasi_event',
        'waktu_start_event',
        'waktu_end_event',
        'jumlah_pendaftar',
        'slug',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'waktu_start_event' => 'datetime',
        'waktu_end_event' => 'datetime',
        'thumbnail_event' => 'array',
    ];

    /**
     * Get the sliders that feature this event.
     */
    public function kontenSliders()
    {
        return $this->hasMany(KontenSlider::class, 'id_event', 'id_event');
    }
}