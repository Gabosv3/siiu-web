<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'technician_id',
        'title_id',
        'description',
        'status',
        'priority',
    ];

    protected static $logAttributes = [
        'user_id',
        'technician_id',
        'title_id',
        'description',
        'status',
        'priority',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'ticket';

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

    public function title()
    {
        return $this->belongsTo(Title::class);
    }
}
