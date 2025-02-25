<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Technician extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;

    // Especifica los campos que se pueden asignar masivamente
    protected $fillable = [
        'user_id', // ID del usuario asociado
        'specialty_id', // Especialidad del técnico
        'available', // Indica si el técnico está disponible
    ];

    // Configurar los atributos que se registrarán
    protected static $logAttributes = [
        'user_id', // ID del usuario asociado
        'specialty_id', // Especialidad del técnico
        'available', // Disponibilidad del técnico
    ];

    // Puedes personalizar el nombre de registro de actividad
    protected static $logName = 'technician';

    /**
     * Relación muchos a uno con el modelo User.
     * Un técnico pertenece a un usuario.
     */    // Relación con el usuario (datos del técnico)
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    
    /**
     * Relación con la especialidad del técnico.
     * Un técnico pertenece a una especialidad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class)->withTrashed();
    }

    
    /**
     * Relación con las asignaciones de este técnico.
     * Un técnico tiene muchas asignaciones.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class)->withTrashed();
    }

    
    /**
     * Relación con las entradas (tickets) asociadas a este técnico.
     * Un técnico puede tener múltiples tickets asignados.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function tickets()
    {
        return $this->hasMany(Ticket::class)->withTrashed();
    }

}
