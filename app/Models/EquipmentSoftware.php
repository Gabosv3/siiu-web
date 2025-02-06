<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentSoftware extends Model
{
    use HasFactory;

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

    // Relación con el equipo (hardware)
    public function hardware()
    {
        return $this->belongsTo(Hardware::class)->withTrashed();
    }

    // Relación con el software
    public function software()
    {
        return $this->belongsTo(Software::class)->withTrashed();
    }

    // Relación con la licencia
    public function license()
    {
        return $this->belongsTo(License::class)->withTrashed();
    }
}
