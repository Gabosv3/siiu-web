<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ModelCharacteristic extends Model
{
    use HasFactory,LogsActivity,SoftDeletes;

    protected $fillable = [
        'model_id',  // Asegúrate de que esta columna se llama 'model_id'
        'characteristic_id',
        'value',
    ];

    protected static $logAttributes = ['model_id', 'characteristic_id', 'value'];

    protected static $logName = 'caracteristicas de modelos';

    /**
     * Define una relación inversa con el modelo `Model`.
     *
     * Cada `ModelCharacteristic` pertenece a un único `Model`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function model()
    {
        return $this->belongsTo(Model::class, 'model_id')->withTrashed(); // La relación debe ser con 'model_id'
    }

    /**
     * Define una relación inversa con el modelo `Characteristic`.
     *
     * Cada `ModelCharacteristic` pertenece a una única `Characteristic`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function characteristic()
    {
        return $this->belongsTo(Characteristic::class)->withTrashed();
    }
}
