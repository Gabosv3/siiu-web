<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Software extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'softwares';

    protected $fillable = [
        'manufacturer_id',
        'software_name',
        'version',
        'type',
        'description',
    ];

    // Configurar los atributos que quieres que se logueen en los cambios
    protected static $logAttributes = [
        'manufacturer_id',
        'software_name',
        'version',
        'type',
        'description',
    ];

    // Guardar todos los cambios (también puede configurarse de otra manera si lo prefieres)
    protected static $logOnlyDirty = true;

    // Descripción personalizada del evento
    protected static $logName = 'software';

   
    /**
     * Personaliza la descripción del evento para que sea mas amigable
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "El software ha sido {$eventName}";
    }
    
    
    /**
     * Relación con el fabricante.
     * Un software pertenece a un fabricante.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class)->withTrashed();
    }

    
    /**
     * Relación con las licencias.
     * Un software puede tener varias licencias.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function licencias()
    {
        return $this->hasMany(License::class)->withTrashed();
    }

}
