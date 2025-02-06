<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HardwareAssignment extends Model
{
    use HasFactory;

    // Campos rellenables
    protected $fillable = [
        'hardware_id',
        'user_id', // Para asignación individual
        'departament_id', // Si se asigna a un departamento
    ];

    // Atributos de logs
    protected static $logsAttributes = ['hardware_id', 'user_id', 'departament_id'];

    // Configuración de logs
    protected static $logName = 'Asignación de equipos';

    /**
     * Relación con el modelo Hardware
     */
    public function hardware()
    {
        return $this->belongsTo(Hardware::class);
    }

    /**
     * Relación con el modelo User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el modelo Department (asumiendo que tienes un modelo Department)
     */
    public function departament()
    {
        return $this->belongsTo(Departament::class);
    }
}

