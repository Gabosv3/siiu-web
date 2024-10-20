<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'technician_id', 'title', 'description', 'status', 'priority',
    ];
    
    // Relación con el modelo Technician
    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
    
    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

      // Relación con las asignaciones (Assignments)
      public function assignments()
      {
          return $this->hasMany(Assignment::class);
      }
}
