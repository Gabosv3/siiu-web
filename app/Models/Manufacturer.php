<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Manufacturer extends Model
{
    use HasFactory,LogsActivity,SoftDeletes;

    //especifica la tabla asociada
    protected $table = 'manufacturers';

    //especifica los campos de la tabla
    protected $fillable = [
        'name', // Nombre del fabricante
        'type', // Descripción del fabricante (opcional)
    ];
    
    protected static $logAttributes = ['name', 'type']; // Atributos que se registrarán en los registros de actividad
    
    
    protected static $logName = 'manufacturer'; // Nombre personalizado para los registros de actividad
    
    
    /**
     * Relación uno a muchos con modelos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function models()
    {
        return $this->hasMany(Model::class)->withTrashed(); // Relación uno a muchos con modelos
    }
}
