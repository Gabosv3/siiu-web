<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Tag extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

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
     * Relación muchos a muchos con el modelo Hardware.
     *
     * Esta función devuelve una relación 'belongsToMany' con el modelo Hardware,
     * indicando que cada etiqueta puede estar asociada a múltiples hardware.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function hardware()
    {
        return $this->belongsToMany(Hardware::class, 'hardware_tag')->withTrashed(); // Clave foránea con la tabla 'hardware_tag'
    }
}
