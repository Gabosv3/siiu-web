<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacionPersonal extends Model
{
    use HasFactory;

    protected $table = 'informacion_personals';

    protected $fillable = [
        'apellidos',
        'nombres',
        'fecha_nacimiento',
        'genero',
        'dui',
        'telefono',
        'user_id',
    ];

     /**
     * Relación muchos a uno con la clase User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
