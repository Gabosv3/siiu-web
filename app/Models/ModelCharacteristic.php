<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelCharacteristic extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',  // Asegúrate de que esta columna se llama 'model_id'
        'characteristic_id',
        'value',
    ];

    protected static $logAttributes = ['model_id', 'characteristic_id', 'value'];

    protected static $logName = 'caracteristicas de modelos';

    public function model()
    {
        return $this->belongsTo(Model::class, 'model_id'); // La relación debe ser con 'model_id'
    }

    public function characteristic()
    {
        return $this->belongsTo(Characteristic::class);
    }
}
