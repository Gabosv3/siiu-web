<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class EquipmentHistory extends Model
{
    use HasFactory,LogsActivity,SoftDeletes;

    protected $table = 'equipment_histories'; // Nombre de la tabla

    // Campos permitidos para la asignación masiva
    protected $fillable = [
        'hardware_id',
        'user_id',
        'action',
        'description',
        'performed_at',
    ];

    // Campos que se van a registrar en el historial
    protected static $logAttributes = [
        'hardware_id',
        'user_id',
        'action',
        'description',
        'performed_at',
    ];

    // Nombre del log
    protected static $logName = 'Historial de Equipos';

    
    /**
     * Relación con el equipo (hardware).
     * Un registro de historial de equipo, pertenece a un equipo (hardware).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipment()
    {
        return $this->belongsTo(Hardware::class)->withTrashed();
    }

    /**
     * Relación con el hardware (Hardware).
     * Un registro de historial de equipo, pertenece a un hardware.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hardware()
    {
        return $this->belongsTo(Hardware::class)->withTrashed();
    }

   
    /**
     * Relación con el usuario (User).
     * Un registro de historial de equipo, fue realizado por un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
