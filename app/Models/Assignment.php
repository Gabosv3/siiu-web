<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Assignment extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

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

    
    /**
     * Relación con el ticket al que se asigna.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class)->withTrashed();
    }

    
    /**
     * Relación con el técnico que se le asigna la tarea.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function technician()
    {
        return $this->belongsTo(Technician::class)->withTrashed();
    }

    
    /**
     * Relación con las asignaciones de hardware que se realizan en la tarea.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hardwareAssignments()
    {
        return $this->hasMany(HardwareAssignment::class)->withTrashed();
    }
}
