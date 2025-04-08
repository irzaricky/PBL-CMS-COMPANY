<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_role',
        'deskripsi',
    ];

    /**
     * Get the users associated with this role.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_role', 'id_role');
    }
}