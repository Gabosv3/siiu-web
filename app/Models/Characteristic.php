<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Characteristic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];



    // Activar el log de cambios
    protected static $logAttributes = ['name', 'description'];

    // Nombre de la tabla
    protected static $name = 'Característica';

    /**
     * Relación con la tabla pivot `model_characteristics`.
     * Una característica puede estar asociada a múltiples modelos.
     */
    public function modelCharacteristics()
    {
        return $this->hasMany(ModelCharacteristic::class, 'characteristic_id');
    }

    /**
     * Relación directa con `models` a través de la tabla pivot.
     */
    public function models()
    {
        return $this->belongsToMany(Models::class, 'model_characteristics')
                    ->withPivot('value')
                    ->withTimestamps();
    }
}
