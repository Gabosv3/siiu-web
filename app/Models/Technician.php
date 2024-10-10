<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Technician extends Model
{
    use HasFactory, LogsActivity;

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
        return $this->belongsTo(User::class);
    }

    // Relación con la especialidad del técnico
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    // Relación con las asignaciones (tareas asignadas a este técnico)
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    // Relación con los tickets asignados a este técnico
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

}
