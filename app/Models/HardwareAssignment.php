<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class HardwareAssignment extends Model
{
    use HasFactory,LogsActivity,SoftDeletes;

    // Campos rellenables
    protected $fillable = [
        'hardware_id',
        'user_id', // Para asignación individual
        'departament_id', // Si se asigna a un departamento
    ];

    // Atributos de logs
    protected static $logsAttributes = ['hardware_id', 'user_id', 'departament_id'];

    // Configuración de logs
    protected static $logName = 'Asignación de equipos';


    /**
     * Relación con el modelo Hardware
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo Hardware,
     * indicando que cada asignación de hardware pertenece a un hardware en particular.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hardware()
    {
        return $this->belongsTo(Hardware::class)->withTrashed();
    }


    /**
     * Relación con el modelo User.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo User,
     * indicando que cada asignación de hardware pertenece a un usuario específico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    
    /**
     * Relación con el modelo Departament.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo Departament,
     * indicando que cada asignación de hardware pertenece a un departamento específico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function departament()
    {
        return $this->belongsTo(Departament::class)->withTrashed();
    }
}
