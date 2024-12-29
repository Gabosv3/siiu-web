<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelCharacteristic extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'characteristic_id',
        'value',
    ];

    public function model()
    {
        return $this->belongsTo(Model::class);
    }

    public function characteristic()
    {
        return $this->belongsTo(Characteristic::class);
    }
    
}
