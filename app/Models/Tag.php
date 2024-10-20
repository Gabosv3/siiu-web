<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Tag extends Model
{
    use HasFactory, LogsActivity;

    // Especifica los campos que se pueden asignar masivamente
    protected $fillable = [
        'name', // Nombre de la etiqueta
    ];

    // Configurar los atributos que se registrarán
    protected static $logAttributes = [
        'name', // Nombre de la etiqueta
    ];

    // Puedes personalizar el nombre de registro de actividad
    protected static $logName = 'tag';

    /**
     * Relación muchos a muchos con hardware.
     * Una etiqueta puede estar asociada a muchos hardware.
     */
    public function hardware()
    {
        return $this->belongsToMany(Hardware::class, 'hardware_tag'); // Clave foránea con la tabla 'hardware_tag'
    }
}
