<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureToggle extends Model
{
    use HasFactory;

    protected $table = 'feature_toggles'; 

    // Menentukan atribut yang bisa diisi
    protected $fillable = [
        'key',
        'label',
        'status_aktif',
    ];

    public $timestamps = false; // Jika tidak ingin menggunakan timestamps
}
