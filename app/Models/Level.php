<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'levels';

    protected $fillable = [
        'level_name',
    ];

    /**
     * Relasi ke tabel User
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_level');
    }
}
