<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unduhan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'unduhan';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_unduhan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_kategori_unduhan',
        'id_user',
        'nama_unduhan',
        'slug',
        'lokasi_file',
        'deskripsi',
        'jumlah_unduhan'
    ];

    /**
     * Get the user that owns this download.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the category that this download belongs to.
     */
    public function kategoriUnduhan()
    {
        return $this->belongsTo(KategoriUnduhan::class, 'id_kategori_unduhan', 'id_kategori_unduhan');
    }

    /**
     * Increment the download count.
     */
    public function incrementDownloadCount()
    {
        $this->increment('jumlah_unduhan');
    }
}