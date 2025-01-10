<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hardware extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'hardware';

    protected $fillable = [
        'category_id',
        'manufacturer_id',
        'model_id',
        'name',
        'conflicts',
        'status',
        'inventory_code',
        'serial_number',
        'warranty_expiration_date',
        'barcode_path'


    ];

    // Configurar los atributos que se registrarán
    protected static $logAttributes = [
        'category_id',
        'manufacturer_id',
        'model_id',
        'name',
        'conflicts',
        'status',
        'inventory_code',
        'serial_number',
        'warranty_expiration_date',
    ];

    // Personalizar el nombre del registro de actividad
    protected static $logName = 'hardware';

    // Relación con la categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación con el usuario (dueño)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la ubicación (departamento)
    public function location()
    {
        return $this->belongsTo(Departament::class);
    }

    // Relación con el fabricante
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    // Relación con el modelo
    public function model()
    {
        return $this->belongsTo(Models::class);
    }

    public function hardwareAssigned()
    {
        return $this->hasOne(HardwareAssignment::class);
    }

    public function equipmentHistories()
    {
        return $this->hasMany(EquipmentHistory::class);
    }
    // Relación muchos a muchos con los sistemas asignados


    // Relación con las licencias

    public function users()
    {
        return $this->belongsToMany(User::class, 'hardware_user', 'hardware_id', 'user_id');
    }

    public function licencias()
    {
        return $this->hasManyThrough(
            License::class,          // Modelo destino
            EquipmentSoftware::class, // Modelo intermedio
            'hardware_id',           // Llave foránea en la tabla intermedia hacia `hardware`
            'software_id',           // Llave foránea en `licenses` hacia `softwares`
            'id',                    // Llave local en `hardware`
            'software_id'            // Llave local en `equipment_softwares`
        );
    }

    public function softwares()
    {
        return $this->belongsToMany(Software::class, 'equipment_softwares', 'hardware_id', 'software_id')
            ->withPivot('license_id') // Incluye el campo extra de la tabla pivot
            ->withTimestamps(); // Para que los timestamps de la tabla pivot se incluyan
    }

    
}
