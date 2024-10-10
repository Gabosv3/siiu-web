<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'technician_id', // El técnico al que se le asigna la tarea
        'ticket_id', // Relación con el ticket
        'task', // La tarea o acción que debe realizarse
        'initial_date', // Fecha de inicio de la tarea
        'status', // Estado de la tarea ('pendiente', 'en progreso', 'completada', etc.)
    ];

    // Relación con el modelo Technician
    
    // Relación con el ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Relación con el técnico
    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}
