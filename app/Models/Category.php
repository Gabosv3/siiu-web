<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'categories';

    protected $fillable = ['name', 'code', 'description', 'image']; // Campos en inglés

    // Configurar los atributos que se registrarán
    protected static $logAttributes = ['name', 'code', 'description', 'image'];

    // Puedes personalizar el nombre del registro de actividad
    protected static $logName = 'category';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($categoria) {
            $maxId = self::max('id') + 1; // Obtener el ID máximo y sumarle 1
            $prefix = strtoupper(substr($categoria->name, 0, 3)); // Primeras tres letras del nombre en mayúsculas
            $codigo = str_pad($maxId, 3, '0', STR_PAD_LEFT); // Número de tres dígitos
            $categoria->code = $prefix . '-' . $codigo; // Asignar el código generado
        });
    }

    // Relación uno a muchos con el modelo Hardware
    public function hardware()
    {
        return $this->hasMany(Hardware::class); // Relación con el modelo Hardware
    }
}
