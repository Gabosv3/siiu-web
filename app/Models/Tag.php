<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
    ];

    // Relación muchos a muchos con hardware
    public function hardware()
    {
        return $this->belongsToMany(Hardware::class, 'hardware_tag');
    }
}
