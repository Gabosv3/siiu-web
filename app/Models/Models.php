<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


class Models extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

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

    /**
     * Relación con las características del modelo.
     * Un modelo tiene muchas características (a través de la tabla pivot `model_characteristics`).
     */
    public function characteristics()
    {
        return $this->belongsToMany(Characteristic::class, 'model_characteristics')
                    ->withPivot('value') // Incluye el valor de la característica en la relación
                    ->withTimestamps();
    }

    /**
     * Relación directa con la tabla `model_characteristics`.
     * Por si necesitas acceder a la tabla pivot directamente.
     */
    public function modelCharacteristics()
    {
        return $this->hasMany(ModelCharacteristic::class, 'model_id'); // Asegúrate de que la relación usa 'model_id'
    }
}

