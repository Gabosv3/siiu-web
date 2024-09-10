<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hardware extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'categoria_id',
        'tipo',
        'conflictos',
        'estado',
        'dueño_id',
        'ubicacion_id',
        'codigo_de_inventario',
        'fabricante_id',
        'modelo_id',
        'sistemas_asignados',
    ];

    // Relación con el fabricante
    public function fabricante()
    {
        return $this->belongsTo(Fabricante::class);
    }

    // Relación con el modelo
    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }

    // Relación muchos a muchos con tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'hardware_tag');
    }

    // Relación con el dueño (usuario)
    public function dueño()
    {
        return $this->belongsTo(User::class, 'dueño_id');
    }

    // Relación con el departamento (ubicación)
    public function ubicacion()
    {
        return $this->belongsTo(Departamento::class, 'ubicacion_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
