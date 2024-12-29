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

    public function ModelCharacteristic()
    {
        return $this->hasMany(ModelCharacteristic::class, 'characteristic_id');
    }
}
