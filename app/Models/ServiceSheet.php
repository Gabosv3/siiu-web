<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceSheet extends Model
{
    use HasFactory,LogsActivity,SoftDeletes;


    protected $table = 'service_sheets';


    protected $fillable = [
        'date', 'department', 'user_id', 'technician_id', 'hardware_id', 'inventory_number',
        'serial_number', 'model', 'status', 'description', 'observations', 'use_supply'
    ];

    protected static $logAttributes = [
        'date', 'department', 'user_id', 'technician_id', 'hardware_id', 'inventory_number',
        'serial_number', 'model', 'status', 'description', 'observations', 'use_supply'
    ];

    protected static $logName = 'Hoja de servicio';


    /**
     * Relación con el modelo User.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo User,
     * indicando que cada hoja de servicio pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Relación con el modelo User.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo User,
     * indicando que cada hoja de servicio pertenece a un técnico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id')->withTrashed();
    }

    /**
     * Relación con el modelo Hardware.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo Hardware,
     * indicando que cada hoja de servicio pertenece a un hardware.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hardware()
    {
        return $this->belongsTo(Hardware::class)->withTrashed();
    }
}
