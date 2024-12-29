<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentHistory extends Model
{
    use HasFactory;

    protected $table = 'equipment_histories'; // Nombre de la tabla

    // Campos permitidos para la asignación masiva
    protected $fillable = [
        'hardware_id',
        'user_id',
        'action',
        'description',
        'performed_at',
    ];

    /**
     * Relación con el equipo (Equipment).
     * Un registro de historial pertenece a un equipo.
     */
    public function equipment()
    {
        return $this->belongsTo(Hardware::class);
    }

    public function hardware()
    {
        return $this->belongsTo(Hardware::class);
    }

    /**
     * Relación con el usuario (User).
     * Un registro de historial puede pertenecer a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
