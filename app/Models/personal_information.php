<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class personal_information extends Model
{
    use HasFactory, LogsActivity;

    // Especifica la tabla asociada
    protected $table = 'personal_informations';

    // Especifica los campos de la tabla
    protected $fillable = [
        'user_id',      // ID del usuario
        'last_name',    // Apellidos
        'first_name',   // Nombres
        'birth_date',   // Fecha de nacimiento
        'gender',       // Género
        'dui',          // DUI
        'phone',        // Teléfono
        
    ];

    // Configurar los atributos que se registrarán
    protected static $logAttributes = [
        'user_id',      // ID del usuario
        'last_name',    // Apellidos
        'first_name',   // Nombres
        'birth_date',   // Fecha de nacimiento
        'gender',       // Género
        'dui',          // DUI
        'phone',        // Teléfono
        
    ];

    // Puedes personalizar el nombre de registro de actividad
    protected static $logName = 'personal_informations';

    /**
     * Relación muchos a uno con la clase User.
     * Un registro de información personal pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class); // Clave foránea con la tabla 'users'
    }
}
