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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class)->withTrashed(); // Clave foránea con la tabla 'manufacturers'
    }


    /**
     * Relación muchos a muchos con características.
     *
     * Un modelo puede tener múltiples características, y una característica puede
     * estar asociada a múltiples modelos. Esta relación se gestiona a través de
     * la tabla pivote `model_characteristics`, que incluye un campo `value` para
     * almacenar un valor específico para cada característica en el contexto del modelo.
     * La relación también registra las marcas de tiempo de creación y actualización.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function characteristics()
    {
        return $this->belongsToMany(Characteristic::class, 'model_characteristics')
            ->withPivot('value') // Incluye el valor de la característica en la relación
            ->withTimestamps()->withTrashed();
    }


    /**
     * Relación con la tabla `model_characteristics`.
     * Un modelo puede tener muchas características.
     * La relación se establece mediante la columna `model_id`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modelCharacteristics()
    {
        return $this->hasMany(ModelCharacteristic::class, 'models_id')->withTrashed(); // Asegúrate de que la relación usa 'model_id'
    }
}
