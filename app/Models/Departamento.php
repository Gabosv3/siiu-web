<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departamento extends Model
{
    use HasFactory,SoftDeletes;
     // Especifica la tabla asociada (opcional si sigue la convención de nombres)
     protected $table = 'departamentos';

     // Especifica los campos que se pueden asignar masivamente
     protected $fillable = ['nombre'];

     protected static function boot()
     {
         parent::boot();
 
         static::creating(function ($departamento) {
             $maxId = self::max('id') + 1;
             $departamento->codigo = 'DEP-' . $maxId;
         });
     }

     public function users()
     {
         return $this->hasMany(User::class);
     }
}
