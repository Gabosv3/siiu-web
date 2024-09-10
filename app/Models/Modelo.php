<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre',
        'fabricante_id',
    ];

    // Relación con el fabricante
    public function fabricante()
    {
        return $this->belongsTo(Fabricante::class);
    }
}
