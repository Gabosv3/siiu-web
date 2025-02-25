<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class EquipmentSoftware extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'equipment_softwares';

    protected $fillable = [
        'hardware_id',    // Relación con el equipo
        'software_id',    // Relación con el software
        'license_id',     // Relación con la licencia (opcional)
    ];

    protected static $logAttributes = [
        'hardware_id',    // Relación con el equipo
        'software_id',    // Relación con el software
        'license_id',     // Relación con la licencia (opcional)
    ];

    protected static $logName = 'equipo_software';


    /**
     * Relación con el hardware (Hardware).
     * Un software en un equipo, pertenece a un hardware.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hardware()
    {
        return $this->belongsTo(Hardware::class)->withTrashed();
    }


    /**
     * Relación con el software (Software).
     * Un software en un equipo, pertenece a un software.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function software()
    {
        return $this->belongsTo(Software::class)->withTrashed();
    }


    /**
     * Relación con la licencia (License).
     * Un software en un equipo, puede tener una licencia asignada.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function license()
    {
        return $this->belongsTo(License::class)->withTrashed();
    }
}
