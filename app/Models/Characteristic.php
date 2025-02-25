<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Characteristic extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'description',
    ];

    // Activar el log de cambios
    protected static $logAttributes = ['name', 'description'];

    // Nombre de la tabla
    protected static $name = 'Característica';

    // Nombre de la actividad
    protected static $logName = 'Características';

   
    /**
     * Relación con la tabla `model_characteristics`.
     * Una característica puede estar asociada a múltiples modelos.
     * La relación se establece mediante la columna `characteristic_id`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modelCharacteristics()
    {
        return $this->hasMany(ModelCharacteristic::class, 'characteristic_id')->withTrashed();
    }

    /**
     * Relación con la tabla `models`.
     * Una característica puede estar asociada a múltiples modelos.
     * La relación incluye la columna `value` de la tabla pivot `model_characteristics`.
     * La relación incluye las marcas de tiempo de creación y actualización.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function models()
    {
        return $this->belongsToMany(Models::class, 'model_characteristics')
                    ->withPivot('value')
                    ->withTimestamps()->withTrashed();
    }
}
