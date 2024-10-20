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
        'hardware_id', // Clave foránea opcional a la tabla 'equipos'
        'license_key',// Clave de la licencia
        'purchase_date',// Fecha de compra de la licencia
        'expiration_date', // Fecha de expiración de la licencia
        'status'// Estado de la licencia
    ];

    // Configurar los atributos que se registrarán
    protected static $logAttributes = [
        'license_key',
        'software_name',
        'purchase_date',
        'expiration_date',
        'status',
    ];

    // Puedes personalizar el nombre de registro de actividad
    protected static $logName = 'license';

     // Cada licencia pertenece a un software
     public function software()
    {
        return $this->belongsTo(Software::class)->withTrashed();
    }
 
     // Una licencia puede estar asignada a un equipo
     public function equipo()
     {
         return $this->belongsTo(Hardware::class)->withTrashed();
     }
}
