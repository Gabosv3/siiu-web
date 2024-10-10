<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Software extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'softwares';

    protected $fillable = [
        'manufacturer_id',
        'software_name',
        'version',
        'description',
    ];

    // Configurar los atributos que quieres que se logueen en los cambios
    protected static $logAttributes = [
        'manufacturer_id',
        'software_name',
        'version',
        'description',
    ];

    // Guardar todos los cambios (también puede configurarse de otra manera si lo prefieres)
    protected static $logOnlyDirty = true;

    // Descripción personalizada del evento
    protected static $logName = 'software';

    // Función para registrar el evento de creación del log
    public function getDescriptionForEvent(string $eventName): string
    {
        return "El software ha sido {$eventName}";
    }
    // Relación con la fabricante
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    // Relación con la licencia
    public function licencias()
    {
        return $this->hasMany(License::class);
    }

}
