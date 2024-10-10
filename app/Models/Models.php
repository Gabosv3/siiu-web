<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Models extends Model
{
    use HasFactory, LogsActivity;

    // Especifica el nombre de la tabla
    protected $table = 'models';

    // Especifica los campos que se pueden asignar masivamente
    protected $fillable = [
        'name',          // Nombre
        'manufacturer_id', // ID del fabricante
    ];

    // Configurar los atributos que se registrarán
    protected static $logAttributes = [
        'name',          // Nombre
        'manufacturer_id', // ID del fabricante
    ];

    // Puedes personalizar el nombre de registro de actividad
    protected static $logName = 'modelo';

    /**
     * Relación con el fabricante.
     * Un modelo pertenece a un fabricante.
     */
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class); // Clave foránea con la tabla 'manufacturers'
    }
}
