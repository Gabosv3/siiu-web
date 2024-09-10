<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Software extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'softwares';

    protected $fillable = [
        'nombre_software',
        'version',
        'fabricante',
        'asignada',
        'ubicacion',
        'clasificacion_licencia',
        'tipo',
        'descripcion',
        'clave_licencia',
        'fecha_compra',
    ];

}
