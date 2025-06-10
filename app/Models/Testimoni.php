<?php

namespace App\Models;

use App\Enums\ContentStatus;
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
        'id_testimoni_produk',
        'id_testimoni_lowongan',
        'id_testimoni_event',  
        'id_testimoni_artikel',
    ];

    /**
     * Get the user that created this testimonial.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function testimoniProduk()
    {
        return $this->belongsTo(TestimoniProduk::class, 'id_testimoni_produk', 'id_testimoni_produk');
    }

    public function testimoniLowongan()
    {
        return $this->belongsTo(TestimoniLowongan::class, 'id_testimoni_lowongan', 'id_testimoni_lowongan');
    }

    public function testimoniEvent()
    {
        return $this->belongsTo(TestimoniEvent::class, 'id_testimoni_event', 'id_testimoni_event');
    }

    public function testimoniArtikel()
    {
        return $this->belongsTo(TestimoniArtikel::class, 'id_testimoni_artikel', 'id_testimoni_artikel');
    }

    // Helper method to get the actual testimonial data
    public function getTestimonialData()
    {
        if ($this->id_testimoni_produk) {
            return $this->testimoniProduk;
        } elseif ($this->id_testimoni_lowongan) {
            return $this->testimoniLowongan;
        } elseif ($this->id_testimoni_event) {
            return $this->testimoniEvent;
        } elseif ($this->id_testimoni_artikel) {
            return $this->testimoniArtikel;
        }
        return null;
    }

    // Helper method to get testimonial type
    public function getTestimonialType()
    {
        if ($this->id_testimoni_produk) return 'Produk';
        if ($this->id_testimoni_lowongan) return 'Lowongan';
        if ($this->id_testimoni_event) return 'Event';
        if ($this->id_testimoni_artikel) return 'Artikel';
        return 'Unknown';
    }
}