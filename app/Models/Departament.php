<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Departament extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    // Especifica la tabla asociada (opcional si sigue la convención de nombres)
    protected $table = 'departaments';

    // Especifica los campos que se pueden asignar masivamente
    protected $fillable = [
        'code',       // Código del departamento
        'name',       // Nombre del departamento
        'manager',    // Encargado del departamento
        'description', // Descripción del departamento
        'latitude',   // Latitud del departamento
        'longitude',  // Longitud del departamento
    ];

    // Configurar los atributos que se registrarán
    protected static $logAttributes = ['code', 'name', 'manager', 'description', 'latitude', 'longitude'];

    // Puedes personalizar el mensaje de registro de actividad
    protected static $logName = 'department';

    
    /**
     * Relación uno a muchos con la clase User.
     * Un departamento puede tener múltiples usuarios asignados.
     */


    public function users()
    {
        return $this->hasMany(User::class)->withTrashed();
    }

    
    /**
     * Relación: Un departamento tiene un encargado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager')->withTrashed();
    }

    
    /**
     * Relación uno a muchos con el modelo HardwareAssignment.
     *
     * Un departamento puede tener múltiples asignaciones de hardware asociadas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function hardwareAssignments()
    {
        return $this->hasMany(HardwareAssignment::class)->withTrashed();
    }
}
