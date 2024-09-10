<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'codigo', 'descripcion', 'imagen'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($categoria) {
            $maxId = self::max('id') + 1;
            $prefix = strtoupper(substr($categoria->nombre, 0, 3)); // Primeras tres letras del nombre en mayúsculas
            $codigo = str_pad($maxId, 3, '0', STR_PAD_LEFT); // Número de tres dígitos
            $categoria->codigo = $prefix . '-' . $codigo;
        });
    }

    public function hardware()
    {
        return $this->hasMany(Hardware::class);
    }
}
