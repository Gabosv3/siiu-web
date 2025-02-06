<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'technician_id',
        'ticket_id',
        'task',
        'initial_date',
        'status',
    ];

    protected $casts = [
        'initial_date' => 'datetime',
    ];

    // Configuración de registro de actividad
    protected static $logAttributes = ['technician_id', 'ticket_id', 'task', 'initial_date', 'status'];
    protected static $logName = 'asignaciones';

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

    // Relación con HardwareAssignment
    public function hardwareAssignments()
    {
        return $this->hasMany(HardwareAssignment::class);
    }
}
