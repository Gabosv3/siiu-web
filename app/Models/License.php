<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class License extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;

    // Especifica el nombre de la tabla
    protected $table = 'licenses';

    // Especifica los campos que se pueden asignar masivamente
    protected $fillable = [
        'software_id',// Clave primaria de la licencia
        'license_key',// Clave de la licencia
        'purchase_date',// Fecha de compra de la licencia
        'expiration_date', // Fecha de expiración de la licencia
        'max_devices', // Cantidad de equipos permitidos
        'used_devices', // Cantidad de equipos en uso
        'status'// Estado de la licencia
    ];

    // Configurar los atributos que se registrarán
    protected static $logAttributes = [
        'license_key',
        'software_name',
        'purchase_date',
        'expiration_date',
        'max_devices',
        'status',
    ];

    // Puedes personalizar el nombre de registro de actividad
    protected static $logName = 'licencias';

    
    /**
     * Relación con el software (Software).
     * Una licencia pertenece a un software.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     public function software()
    {
        return $this->belongsTo(Software::class)->withTrashed();
    }

     
     /**
      * Relación con el equipo (Hardware).
      * Una licencia se asigna a un equipo.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function equipo()
     {
         return $this->belongsTo(Hardware::class)->withTrashed();
     }
}
