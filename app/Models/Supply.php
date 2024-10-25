<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Supply extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'category_id',
        'manufacturer_id',
        'model_id',
        'name',
        'quantity',
        'unit',
        'description',
        'status',
    ];

    protected static $logAttributes = [
        'category_id',
        'manufacturer_id',
        'model_id',
        'name', 
        'quantity',
        'unit',
        'description',
        'status',
    ];

    protected static $logName = 'supply';

    // Relación con la categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación con el fabricante
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    // Relación con el modelo
    public function model()
    {
        return $this->belongsTo(Models::class);
    }



}
