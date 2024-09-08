<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departamento extends Model
{
    use HasFactory, SoftDeletes;
    // Especifica la tabla asociada (opcional si sigue la convención de nombres)
    protected $table = 'departamentos';

    // Especifica los campos que se pueden asignar masivamente
    protected $fillable = [
        'codigo',
        'nombre',
        'encargado',
        'descripcion',
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function encargado()
    {
        return $this->belongsTo(User::class, 'encargado');
    }

}
