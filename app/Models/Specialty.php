<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Specialty extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',  // Nombre de la especialidad
    ];
    

    // Puedes agregar relaciones si las necesitas
    // Ejemplo: una especialidad puede tener muchos técnicos
    public function technicians()
    {
        return $this->hasMany(Technician::class);
    }


}
