<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Specialty extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'name',  // Nombre de la especialidad
    ];

    protected static $logAttributes = ['name'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'specialty';


    
    /**
     * Devuelve una lista de todos los tecnicos que
     * est n relacionados con esta especialidad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function technicians()
    {
        return $this->hasMany(Technician::class)->withTrashed();
    }


}
