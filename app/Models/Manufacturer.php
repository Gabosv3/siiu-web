<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Manufacturer extends Model
{
    use HasFactory, LogsActivity;

    //especifica la tabla asociada
    protected $table = 'manufacturers';

    //especifica los campos de la tabla
    protected $fillable = [
        'name', // Nombre del fabricante
    ];
    
    // Configure the attributes that will be logged
    protected static $logAttributes = ['name']; // Atributos que se registrarán en los registros de actividad
    
    // You can customize the log message
    protected static $logName = 'manufacturer'; // Nombre personalizado para los registros de actividad
    
    // One-to-many relationship with models (a manufacturer has many models)
    public function models()
    {
        return $this->hasMany(Model::class); // Relación uno a muchos con modelos
    }
}
